<?php
$iconPath = "icon/apple-touch-icon.png";
include 'php/icon.php';
echo "<link rel='icon' href='$iconPath' type='image/x-icon'>";
echo "<link rel='icon' href='$iconPath?" . time() . "' type='image/x-icon'>";

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neko</title>
    <link rel="<?php echo $iconPath; ?>" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .hidden-text, .dots {
            display: none;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1 class="welcome-text">
            <img src="<?php echo $iconPath; ?>" alt="Hoş Geldiniz İkonu" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 20px;">
            Hoş Geldiniz! ようこそ!
        </h1>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="php/hakkinda.php">Hakkında</a></li>
                <li><a href="php/iletisim.php">İletişim</a></li>
                <li><a href="php/giris.php">Giriş</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <h2>一緒に日本を旅しましょう</h2>
            <p>日本</p>
        </div>
    </section>

    <section class="destinations">
        <div class="container">
            <h2>Japonya</h2>
            <div class="destination-cards">

                <?php
                session_start();
                include 'php/database.php';

                global $baglan;
                $sql = "SELECT baslik, konu, resim FROM yerler";
                $result = $baglan->query($sql);

                if ($result->num_rows > 0) {

                    while($row = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '    <img src="' . $row["resim"] . '" alt="' . $row["baslik"] . '">';
                        echo '    <h3>' . $row["baslik"] . '</h3>';
                        echo '    <p onclick="toggleText(this)">' . substr($row["konu"], 0, 50);
                        echo '        <span class="dots">...</span>';
                        echo '        <span class="hidden-text">' . substr($row["konu"], 50) . '</span>';
                        echo '    </p>';
                        echo '</div>';
                    }
                } else {
                    echo "0 sonuç";
                }

                $baglan->close();
                ?>

            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; Bu site Ceydanur Gökdemir tarafından yapılmıştır.</p>
    </div>
</footer>

<script>
    function toggleText(element) {
        const hiddenText = element.querySelector('.hidden-text');
        const dots = element.querySelector('.dots');
        if (hiddenText.style.display === 'none' || hiddenText.style.display === '') {
            hiddenText.style.display = 'inline';
            dots.style.display = 'none';
        } else {
            hiddenText.style.display = 'none';
            dots.style.display = 'inline';
        }
    }
</script>
</body>
</html>
