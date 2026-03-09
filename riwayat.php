<?php
session_start();
include "koneksi.php";

// Cek login anggota
if(!isset($_SESSION['anggota'])){
    header("Location: login_anggota.php");
    exit;
}

// Ambil ID anggota
$username = $_SESSION['anggota'];
$q = mysqli_query($conn, "SELECT id_anggota FROM anggota WHERE username='$username'");
$row = mysqli_fetch_assoc($q);
$id_anggota = $row['id_anggota'] ?? 0;

// Ambil riwayat peminjaman
$sql = "SELECT t.id_transaksi, b.judul, b.penulis, t.tanggal_pinjam, t.tanggal_kembali, t.status
        FROM transaksi t
        JOIN buku b ON t.id_buku = b.id_buku
        WHERE t.id_anggota = $id_anggota
        ORDER BY t.tanggal_pinjam DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Riwayat Peminjaman</title>
<style>
body { font-family: Arial; margin:0; display:flex; min-height:100vh; background:#f4f4f4; }
.sidebar { width:220px; background:#1e40af; color:white; padding:20px; flex-shrink:0; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; color:white; text-decoration:none; margin:10px 0; padding:10px; border-radius:6px; }
.sidebar a:hover { background:#2563eb; }
.main { flex-grow:1; padding:40px; }
h1 { color:#1e3a8a; }
table { width:100%; border-collapse: collapse; background:white; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#1e40af; color:white; }
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
<h1>📄 Riwayat Peminjaman</h1>
<table>
<tr>
<th>No</th>
<th>Judul Buku</th>
<th>Pengarang</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>
</tr>

<?php
$no=1;
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$no++."</td>";
        echo "<td>".htmlspecialchars($row['judul'])."</td>";
        echo "<td>".htmlspecialchars($row['penulis'])."</td>";
        echo "<td>".htmlspecialchars($row['tanggal_pinjam'])."</td>";
        echo "<td>".($row['tanggal_kembali'] ?? '-')."</td>";
        echo "<td>".htmlspecialchars($row['status'])."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada riwayat peminjaman</td></tr>";
}
?>
</table>
</div>
</body>
</html>