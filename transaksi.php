<?php
session_start();
include "koneksi.php";

/* ================= CEK LOGIN ANGGOTA ================= */
if(!isset($_SESSION['anggota']) || empty($_SESSION['anggota'])){
    header("Location: login_anggota.php");
    exit;
}

$username = $_SESSION['anggota'];

/* ================= AMBIL ID ANGGOTA ================= */
$q = mysqli_query($conn, "SELECT id_anggota FROM anggota WHERE username='$username'");

if($q && mysqli_num_rows($q) > 0){
    $row = mysqli_fetch_assoc($q);
    $id_anggota = $row['id_anggota'];
} else {
    session_destroy();
    header("Location: login_anggota.php");
    exit;
}

/* ================= PROSES PINJAM ================= */
if(isset($_POST['pinjam']) && isset($_POST['id_buku'])){
    $id_buku = intval($_POST['id_buku']);

    $cek = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku=$id_buku");

    if($cek && mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        $stok = $data['stok'];

        if($stok > 0){

            mysqli_query($conn, "UPDATE buku 
                                 SET stok=stok-1 
                                 WHERE id_buku=$id_buku");

            mysqli_query($conn, "INSERT INTO transaksi 
                (id_anggota,id_buku,tanggal_pinjam,status)
                VALUES ($id_anggota,$id_buku,NOW(),'Dipinjam')");

            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
    }
}

/* ================= PROSES KEMBALI ================= */
if(isset($_POST['kembali']) && isset($_POST['id_transaksi'])){
    $id_transaksi = intval($_POST['id_transaksi']);

    $cek = mysqli_query($conn, 
        "SELECT id_buku FROM transaksi 
         WHERE id_transaksi=$id_transaksi 
         AND id_anggota=$id_anggota");

    if($cek && mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        $id_buku = $data['id_buku'];

        mysqli_query($conn,"UPDATE transaksi 
                            SET tanggal_kembali=NOW(), status='Kembali'
                            WHERE id_transaksi=$id_transaksi");

        mysqli_query($conn,"UPDATE buku 
                            SET stok=stok+1 
                            WHERE id_buku=$id_buku");

        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

/* ================= AMBIL DATA ================= */
$data = mysqli_query($conn, "SELECT * FROM buku");

$transaksi = mysqli_query($conn, "
    SELECT t.id_transaksi, b.judul, b.penulis,
           t.tanggal_pinjam, t.tanggal_kembali, t.status
    FROM transaksi t
    JOIN buku b ON t.id_buku=b.id_buku
    WHERE t.id_anggota=$id_anggota
    ORDER BY t.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Transaksi Anggota</title>
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
</style>
</head>
<body>

<div class="sidebar">
<h2>ANGGOTA</h2>
<a href="dashboard_anggota.php">Dashboard</a>
<a href="transaksi_anggota.php">Pinjam/Kembalikan Buku</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">

<h1>📚 Daftar Buku</h1>
<table>
<tr>
<th>No</th>
<th>Judul</th>
<th>Penulis</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php $no=1; while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($d['judul']); ?></td>
<td><?= htmlspecialchars($d['penulis']); ?></td>
<td><?= $d['stok']; ?></td>
<td>
<?php if($d['stok'] > 0){ ?>
<form method="POST">
<input type="hidden" name="id_buku" value="<?= $d['id_buku']; ?>">
<button type="submit" name="pinjam">Pinjam</button>
</form>
<?php } else { echo "Stok Habis"; } ?>
</td>
</tr>
<?php } ?>
</table>

<h1>📄 Riwayat Peminjaman</h1>
<table>
<tr>
<th>No</th>
<th>Judul</th>
<th>Penulis</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php $no=1; while($t=mysqli_fetch_assoc($transaksi)){ ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($t['judul']); ?></td>
<td><?= htmlspecialchars($t['penulis']); ?></td>
<td><?= $t['tanggal_pinjam']; ?></td>
<td><?= $t['tanggal_kembali'] ? $t['tanggal_kembali'] : '-'; ?></td>
<td><?= $t['status']; ?></td>
<td>
<?php if($t['status']=='Dipinjam'){ ?>
<form method="POST">
<input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi']; ?>">
<button type="submit" name="kembali">Kembalikan</button>
</form>
<?php } else { echo "-"; } ?>
</td>
</tr>
<?php } ?>
</table>

</div>
</body>
</html>