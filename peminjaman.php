<?php
session_start();
include "koneksi.php";

// Cek login admin
if(!isset($_SESSION['admin'])){
    header("Location: login_admin.php");
    exit;
}

// Update status buku dikembalikan
if(isset($_GET['kembali'])){
    $id = intval($_GET['kembali']);
    $tgl_kembali = date('Y-m-d');
    mysqli_query($conn, "UPDATE transaksi SET status='Dikembalikan', tgl_kembali='$tgl_kembali' WHERE id=$id");
    header("Location: peminjaman.php");
    exit;
}

// Ambil semua transaksi beserta info buku dan anggota
$query = "
SELECT t.id AS id_transaksi, a.username, a.nama_lengkap, b.judul, b.pengarang, t.tgl_pinjam, t.tgl_kembali, t.status
FROM transaksi t
JOIN anggota a ON t.id_anggota = a.id
JOIN buku b ON t.id_buku = b.id
ORDER BY t.tgl_pinjam DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Data Peminjaman Buku</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:20px; }
h1 { color:#1e3a8a; }
table { width:100%; border-collapse: collapse; background:white; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#1e40af; color:white; }
a.button { padding:6px 12px; background:#1e40af; color:white; text-decoration:none; border-radius:4px; }
a.button:hover { background:#2563eb; }
</style>
</head>
<body>

<h1>Data Peminjaman Buku</h1>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Pengarang</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
<?php if($result && mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['id_transaksi']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['nama_lengkap']; ?></td>
        <td><?php echo $row['judul']; ?></td>
        <td><?php echo $row['pengarang']; ?></td>
        <td><?php echo $row['tgl_pinjam']; ?></td>
        <td><?php echo $row['tgl_kembali'] ?? '-'; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <?php if($row['status'] == 'Dipinjam'): ?>
            <a href="peminjaman.php?kembali=<?php echo $row['id_transaksi']; ?>" class="button" onclick="return confirm('Konfirmasi buku dikembalikan?')">Kembalikan</a>
            <?php else: ?>
            -
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="9" style="text-align:center;">Belum ada transaksi peminjaman</td></tr>
<?php endif; ?>
</table>

</body>
</html>