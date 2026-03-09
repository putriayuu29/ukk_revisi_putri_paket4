<?php
session_start();
if(!isset($_SESSION['anggota'])){
    header("Location: login_anggota.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard Anggota</title>
<style>
body { font-family: Arial; margin:0; display:flex; min-height:100vh; background:#f4f4f4; }
.sidebar { width:220px; background:#1e40af; color:white; padding:20px; flex-shrink:0; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; color:white; text-decoration:none; margin:10px 0; padding:10px; border-radius:6px; }
.sidebar a:hover { background:#2563eb; }
.main { flex-grow:1; padding:40px; }
.main h1 { color:#1e3a8a; }
.card-container { display:flex; flex-wrap:wrap; gap:20px; margin-top:30px; }
.card-link { text-decoration:none; color:inherit; flex:1 1 200px; }
.card { background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); transition:0.2s; }
.card:hover { transform:translateY(-5px); }
.card h3 { margin-top:0; }
.card p { color:#555; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>ANGGOTA</h2>
    <a href="dashboard_anggota.php">Dashboard</a>
    <a href="transaksi.php">Pinjam Buku</a>
    <a href="riwayat.php">Riwayat</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
<h1>Selamat Datang, <?php echo $_SESSION['anggota']; ?>!</h1>

<div class="card-container">
    <a href="transaksi.php" class="card-link">
        <div class="card">
            <h3>📚 Pinjam Buku</h3>
            <p>Lihat daftar buku dan pinjam</p>
        </div>
    </a>

    <a href="riwayat.php" class="card-link">
        <div class="card">
            <h3>📄 Riwayat</h3>
            <p>Lihat transaksi peminjaman</p>
        </div>
    </a>

    <a href="logout.php" class="card-link">
        <div class="card">
            <h3>🚪 Logout</h3>
            <p>Keluar dari sistem</p>
        </div>
    </a>
</div>

</div>

</body>
</html>