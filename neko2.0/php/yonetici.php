<?php

global $pages;

session_start();

$session_duration = 300;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_duration)) {
    session_unset();
    session_destroy();
    header("Location: cikis.php");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

$cookie_params = session_get_cookie_params();
setcookie(session_name(), session_id(), time() + $session_duration, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);

include 'database.php';
global $baglan;
include 'icon.php';

$uploadMessage = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['icon'])) {
    $targetDir = "../icon/";
    $targetFile = $targetDir . basename($_FILES["icon"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES["icon"]["tmp_name"], $targetFile)) {
            $uploadMessage = "İkon başarıyla yüklendi.";
            $uploadMessageType = "success";
            file_put_contents('icon.php', "<?php\n\$iconPath = '$targetFile';\n");
        } else {
            $uploadMessage = "Dosya yüklenirken bir hata oluştu.";
            $uploadMessageType = "error";
        }
    } else {
        $uploadMessage = "Yalnızca JPG, JPEG, PNG ve GIF formatları destekleniyor.";
    }
}

function getRecords($baglan, $page) {
    $query = "SELECT * FROM yerler WHERE page='$page'";
    $result = $baglan->query($query);
    return $result;
}

function addRecord($baglan, $baslik, $konu, $resim) {
    $baslik = $baglan->real_escape_string($baslik);
    $konu = $baglan->real_escape_string($konu);
    $resim = $baglan->real_escape_string($resim);

    $query = "INSERT INTO yerler (baslik, konu, resim) VALUES ('$baslik', '$konu', '$resim')";
    $baglan->query($query);
}

function updateRecord($baglan, $id, $baslik, $konu, $resim){
    $id = intval($id);
    $baslik = $baglan->real_escape_string($baslik);
    $konu = $baglan->real_escape_string($konu);
    $resim = $baglan->real_escape_string($resim);

    $query = "UPDATE yerler SET baslik='$baslik', konu='$konu', resim='$resim' WHERE id=$id";
    $baglan->query($query);
}

function deleteRecord($baglan, $id) {
    $id = intval($id);
    $query = "DELETE FROM yerler WHERE id=$id";
    $baglan->query($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        addRecord($baglan, $_POST['baslik'], $_POST['konu'], $_POST['resim']);
    } elseif (isset($_POST['update'])) {
        updateRecord($baglan, $_POST['id'], $_POST['baslik'], $_POST['konu'], $_POST['resim']);
    } elseif (isset($_POST['delete'])) {
        deleteRecord($baglan, $_POST['id']);
    }
}

function getAllyerler() {
    global $baglan;
    $sql = "SELECT * FROM yerler";
    return $baglan->query($sql);
}

$yerler = getAllyerler();

$iconPath = "../icon/apple-touch-icon.png";
include 'icon.php';
echo "<link rel='icon' href='$iconPath' type='image/x-icon'>";
echo "<link rel='icon' href='$iconPath?" . time() . "' type='image/x-icon'>";

?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi</title>
    <link rel="<?php echo $iconPath; ?>" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Yönetici Girişi</h1>
        <img src="<?php echo $iconPath; ?>" alt="Hoş Geldiniz İkonu" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 20px;">
        <nav>
            <ul>
                <li><a href="../index.php">Ana Sayfa</a></li>
                <li><a href="hakkinda.php">Hakkında</a></li>
                <li><a href="iletisim.php">İletişim</a></li>
                <li><a href="cikis.php">Çıkış</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h2>wakannai</h2>
        <p></p>

        <?php
        include 'database.php';
        global $baglan;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_content'])) {
            $hakkindaContent = $baglan->real_escape_string($_POST['hakkinda_content']);
            $iletisimContent = $baglan->real_escape_string($_POST['iletisim_content']);

            // Veritabanında güncelleme yap
            $baglan->query("UPDATE sayfalar SET content='$hakkindaContent' WHERE sayfa_isim='hakkinda'");
            $baglan->query("UPDATE sayfalar SET content='$iletisimContent' WHERE sayfa_isim='iletisim'");

            echo "İçerikler başarıyla güncellendi.";
        }


        $hakkinda = $baglan->query("SELECT content FROM sayfalar WHERE sayfa_isim='hakkinda'")->fetch_assoc();
        $iletisim = $baglan->query("SELECT content FROM sayfalar WHERE sayfa_isim='iletisim'")->fetch_assoc();


        ?>

        <form action="yonetici.php" method="post">
            <h3>Hakkında Sayfası</h3>
            <textarea name="hakkinda_content" rows="10" cols="50"><?php echo $hakkinda['content']; ?></textarea>
            <button type="submit" name="update_content">Güncelle</button>

            <h3>İletişim Sayfası</h3>
            <textarea name="iletisim_content" rows="10" cols="50"><?php echo $iletisim['content']; ?></textarea>

            <button type="submit" name="update_content">Güncelle</button>
        </form>



        <h3>İkon Yükle</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="iconUpload">Yeni İkon Yükleyin:</label>
            <input type="file" name="icon" id="iconUpload" accept="image/*">
            <button type="submit">Yükle</button>
        </form>
        <?php if (!empty($uploadMessage)): ?> <p class="upload-message <?php echo $uploadMessageType; ?>"> <?php echo $uploadMessage; ?> </p> <?php endif; ?>


        <h3>Yeni Kayıt Ekle</h3>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <label for="baslik">Başlık:</label>
            <input type="text" id="baslik" name="baslik" required>
            <label for="konu">Konu:</label>
            <input type="text" id="konu" name="konu" required>
            <label for="resim">Resim:</label>
            <input type="text" id="resim" name="resim" required>
            <button type="submit" name="add">Ekle</button>
        </form>

        <h3>Tüm Veriler</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Konu</th>
                <th>Resim</th>
                <th>İşlemler</th>
            </tr>
            <?php while ($row = $yerler->fetch_assoc()) { ?>
                <tr>
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <td><?php echo $row["id"]; ?></td>
                        <td>
                            <input type="text" name="baslik" value="<?php echo $row["baslik"]; ?>">
                        </td>
                        <td>
                            <input type="text" name="konu" value="<?php echo $row["konu"]; ?>">
                        </td>
                        <td>
                            <input type="text" name="resim" value="<?php echo $row["resim"]; ?>">
                        </td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                            <button type="submit" name="update">Güncelle</button>
                            <button type="submit" name="delete">Sil</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>
<br><br><br><br><br><br><br><br><br><br><br>
<footer>
    <div class="container">
        <p>&copy; Bu site Ceydanur Gökdemir tarafından yapılmıştır.</p>
    </div>
</footer>
</body>
</html>