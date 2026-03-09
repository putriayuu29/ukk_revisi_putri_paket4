<?php
session_start();

// Hapus semua session
$_SESSION = array();
session_destroy();

// Redirect ke login admin
header("Location: login_admin.php");
exit;
?>

