<?php
session_start();
include "koneksi.php";

// Cek login admin
if(!isset($_SESSION['admin'])){
    header("Location: login_admin.php");
    exit;
}

// Proses pengembalian
if(isset($_GET['kembali']) && is_numeric($_GET['kembali'])){
    $id_transaksi = intval($_GET['kembali']);
    $q = mysqli_query($conn, "SELECT id_buku FROM transaksi WHERE id_transaksi=$id_transaksi");
    $row = mysqli_fetch_assoc($q);
    $id_buku = $row['id_buku'];

    // Update transaksi
    mysqli_query($conn, "UPDATE transaksi SET tanggal_kembali=NOW(), status='Kembali' WHERE id_transaksi=$id_transaksi");
    // Tambah stok buku
    mysqli_query($conn, "UPDATE buku SET stok=stok+1 WHERE id_buku=$id_buku");

    header("Location: kembali.php");
    exit;
}

// Ambil transaksi yang masih dipinjam
$result = mysqli_query($conn, "SELECT t.id_transaksi, b.judul, b.penulis, t.tanggal_pinjam
                               FROM transaksi t
                               JOIN buku b ON t.id_buku = b.id_buku
                               WHERE t.status='Dipinjam'");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pengembalian Buku</title>
<style>
table { border-collapse: collapse; width:100%; background:white; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#1e40af; color:white; }
a.button { padding:6px 12px; background:#1e40af; color:white; text-decoration:none; border-radius:4px; }
a.button:hover { background:#2563eb; }
</style>
</head>
<body>
<h1>📚 Pengembalian Buku</h1>
<table>
<tr>
<th>No</th>
<th>Judul</th>
<th>Penulis</th>
<th>Tanggal Pinjam</th>
<th>Aksi</th>
</tr>

<?php
$no=1;
while($row = mysqli_fetch_assoc($result)){
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".htmlspecialchars($row['judul'])."</td>";
    echo "<td>".htmlspecialchars($row['penulis'])."</td>";
    echo "<td>".htmlspecialchars($row['tanggal_pinjam'])."</td>";
    echo "<td><a href='kembali.php?kembali=".$row['id_transaksi']."' class='button' onclick=\"return confirm('Yakin dikembalikan?')\">Kembalikan</a></td>";
    echo "</tr>";
}
?>
</table>
</body>
</html>