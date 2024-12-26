<?php
session_start();
include 'database.php';
global $baglan;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['yonetici'];
    $password = $_POST['123'];


    $sql = "SELECT * FROM kullanicilar WHERE kullanici = ?";
    $stmt = $baglan->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: yonetici.php');
            exit();
        } else {
            $_SESSION['error'] = 'Yanlış şifre!';
        }
    } else {
        $_SESSION['error'] = 'Kullanıcı bulunamadı!';
    }
    header('Location: index.php');
    exit();
}
?>