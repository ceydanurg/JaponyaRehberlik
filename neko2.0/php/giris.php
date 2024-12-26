<?php
session_start();

$iconPath = "../icon/apple-touch-icon.png";
include 'icon.php';
echo "<link rel='icon' href='$iconPath' type='image/x-icon'>";
echo "<link rel='icon' href='$iconPath?" . time() . "' type='image/x-icon'>";


include 'database.php';
global $baglan;

if (isset($_SESSION['giris'])) {
    header("Location: yonetici.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici = $_POST["kullanici"];
    $sifre = $_POST["sifre"];

    $sorgu = $baglan->prepare("SELECT * FROM kullanicilar WHERE kullanici = ?");
    $sorgu->bind_param("s", $kullanici);
    $sorgu->execute();
    $sonuc = $sorgu->get_result();

    if ($sonuc->num_rows > 0) {
        $kullanici_verisi = $sonuc->fetch_assoc();

        if (password_verify($sifre, $kullanici_verisi['sifre'])) {
            $session_lifetime = 300;
            $cookie_lifetime = 300;

            setcookie("kullanici", "msb", time() + $cookie_lifetime, "/");
            $_SESSION["giris"] = sha1(md5("var"));
            $_SESSION['LAST_ACTIVITY'] = time();
            $_SESSION['EXPIRE_TIME'] = $session_lifetime;

            header("Location: yonetici.php");
            exit();
        } else {
            echo "<script>alert('Hatalı şifre!'); window.location.href='giris.php';</script>";
        }
    } else {
        echo "<script>alert('Hatalı kullanıcı adı!'); window.location.href='giris.php';</script>";
    }
}
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
                <li><a href="giris.php">Yönetici</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <form action="giris.php" method="post">
            <label for="kullanici">Kullanıcı adı:</label>
            <input type="text" id="kullanici" name="kullanici" required>

            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre" required>

            <button type="submit">Giriş</button>
        </form>
    </div>
</main>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer>
    <div class="container">
        <p>&copy; Bu site Ceydanur Gökdemir tarafından yapılmıştır.</p>
    </div>
</footer>
</body>
</html>
