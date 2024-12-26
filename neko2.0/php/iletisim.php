<?php

include 'database.php';
global $baglan;
$iletisim = $baglan->query("SELECT content FROM sayfalar WHERE sayfa_isim='iletisim'")->fetch_assoc();


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
    <title>İletişim</title>
    <link rel="<?php echo $iconPath; ?>" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>İletişim</h1>
        <img src="<?php echo $iconPath; ?>" alt="Hoş Geldiniz İkonu" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 20px;">
        <nav>
            <ul>
                <li><a href="../index.php">Ana Sayfa</a></li>
                <li><a href="hakkinda.php">Hakkında</a></li>
                <li><a href="iletisim.php">İletişim</a></li>
                <li><a href="giris.php">Giriş</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section class="contact">
        <div class="container">
            <h2>İletişim</h2>
            <p><?php echo $iletisim['content']; ?></p>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; Bu site Ceydanur Gökdemir tarafından yapılmıştır.</p>
    </div>
</footer>
</body>
</html>