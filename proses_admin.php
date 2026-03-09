<?php
session_start();
include "koneksi.php";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn,"
        SELECT * FROM admin
        WHERE username='$username'
        AND password='$password'
    ");

    if($query && mysqli_num_rows($query) > 0){

        // ✅ INI YANG PALING PENTING
        $_SESSION['admin'] = $username;

        header("Location: dashboard_admin.php");
        exit;

    } else {

        echo "<script>
            alert('Username atau Password salah!');
            window.location='login_admin.php';
        </script>";

    }
}
?>