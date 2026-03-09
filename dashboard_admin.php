<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login_admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
<h2>ADMIN</h2>
<a href="dashboard_admin.php">Dashboard</a>
<a href="buku.php">Data Buku</a>
<a href="anggota.php">Data Anggota</a> <!-- Tambahan -->
<a href="transaksi_admin.php">Transaksi</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">
<h1>Dashboard Admin</h1>

<div class="card-container">

<a href="buku.php" class="card-link">
<div class="card">
<h3>📚 Data Buku</h3>
<p>Kelola buku perpustakaan</p>
</div>
</a>

<a href="transaksi_admin.php" class="card-link">
<div class="card">
<h3>📖 Transaksi</h3>
<p>Peminjaman buku</p>
</div>
</a>

<a href="logout.php" class="card-link">
<div class="card">
<h3>🚪 Logout</h3>
<p>Keluar dari sistem</p>
</div>
</a>

<a href="anggota.php" class="card-link">
<div class="card">
<h3>👥 Data Anggota</h3>
<p>Kelola anggota perpustakaan</p>
</div>
</a>

</div>
</div>

</body>
</html>