<?php
session_start();
include "koneksi.php";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk anggota
    $query = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($query) > 0){
        $_SESSION['anggota'] = $username;
        header("Location: dashboard_anggota.php");
        exit;
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location='login_anggota.php';</script>";
    }
} else {
    header("Location: login_anggota.php");
    exit;
}
?>