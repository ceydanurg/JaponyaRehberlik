<?php
include 'database.php';
global $baglan;

$kullanici = "yonetici";
$sifre = password_hash("123", PASSWORD_DEFAULT);

$sql = $baglan->prepare("INSERT INTO kullanicilar (kullanici, sifre) VALUES (?, ?)");
$sql->bind_param("ss", $kullanici, $sifre);

if ($sql->execute()) {
    echo "Kullanıcı başarıyla oluşturuldu.";
} else {
    echo "Hata: " . $sql->error;
}

$baglan->close();
?>