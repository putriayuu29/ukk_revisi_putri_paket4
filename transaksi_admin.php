<?php
session_start();
include "koneksi.php";

/* ================= CEK LOGIN ADMIN ================= */
if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
    header("Location: login_admin.php");
    exit;
}

/* ================= PROSES KEMBALIKAN ================= */
if(isset($_GET['kembalikan'])){

    $id_transaksi = intval($_GET['kembalikan']);

    $cek = mysqli_query($conn,"
        SELECT id_buku FROM transaksi
        WHERE id_transaksi=$id_transaksi
        AND status='Dipinjam'
    ");

    if($cek && mysqli_num_rows($cek) > 0){

        $data = mysqli_fetch_assoc($cek);
        $id_buku = $data['id_buku'];

        mysqli_query($conn,"
            UPDATE transaksi
            SET status='Kembali',
                tanggal_kembali=NOW()
            WHERE id_transaksi=$id_transaksi
        ");

        mysqli_query($conn,"
            UPDATE buku
            SET stok=stok+1
            WHERE id_buku=$id_buku
        ");
    }

    header("Location: transaksi_admin.php");
    exit;
}

/* ================= AMBIL DATA ================= */
$transaksi = mysqli_query($conn,"
    SELECT t.id_transaksi,
           a.username,
           b.judul,
           b.penulis,
           t.tanggal_pinjam,
           t.tanggal_kembali,
           t.status
    FROM transaksi t
    JOIN anggota a ON t.id_anggota=a.id_anggota
    JOIN buku b ON t.id_buku=b.id_buku
    ORDER BY t.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Transaksi Admin</title>
<style>
body { font-family: Arial; margin:0; display:flex; min-height:100vh; background:#f4f4f4; }
.sidebar { width:220px; background:#1e40af; color:white; padding:20px; }
.sidebar h2 { text-align:center; }
.sidebar a { display:block; color:white; text-decoration:none; margin:10px 0; padding:10px; border-radius:6px; }
.sidebar a:hover { background:#2563eb; }
.main { flex-grow:1; padding:40px; }
h1 { color:#1e3a8a; }
table { width:100%; border-collapse: collapse; background:white; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; }
th { background:#1e40af; color:white; }
button { padding:5px 10px; background:#1e40af; color:white; border:none; cursor:pointer; border-radius:4px; }
button:hover { background:#2563eb; }
a { text-decoration:none; }
</style>
</head>
<body>

<div class="sidebar">
<h2>ADMIN</h2>
<a href="dashboard_admin.php">Dashboard</a>
<a href="transaksi_admin.php">Kelola Transaksi</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">

<h1>📄 Data Transaksi Anggota</h1>

<table>
<tr>
<th>No</th>
<th>Username</th>
<th>Judul</th>
<th>Penulis</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$no=1;
if($transaksi && mysqli_num_rows($transaksi) > 0){
while($t=mysqli_fetch_assoc($transaksi)){
?>

<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($t['username']); ?></td>
<td><?= htmlspecialchars($t['judul']); ?></td>
<td><?= htmlspecialchars($t['penulis']); ?></td>
<td><?= $t['tanggal_pinjam']; ?></td>
<td><?= $t['tanggal_kembali'] ? $t['tanggal_kembali'] : '-'; ?></td>
<td><?= $t['status']; ?></td>
<td>
<?php if($t['status']=='Dipinjam'){ ?>
<a href="?kembalikan=<?= $t['id_transaksi']; ?>">
<button>Kembalikan</button>
</a>
<?php } else { echo "-"; } ?>
</td>
</tr>

<?php
}
} else {
echo "<tr><td colspan='8'>Belum ada transaksi</td></tr>";
}
?>

</table>

</div>
</body>
</html>