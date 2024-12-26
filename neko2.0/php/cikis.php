<?php
session_start();
session_unset();
session_destroy();

setcookie('kullanici', '', time() - 3600, '/');

header("Location: giris.php");
exit();
?>
