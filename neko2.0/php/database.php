<?php
$baglan = new mysqli("localhost", "root", "", "neko");
$baglan->set_charset("utf8");

if ($baglan->connect_error)
{
    exit("Bağlantı hatası: " . $baglan->connect_error);
}
else
{
}
?>
