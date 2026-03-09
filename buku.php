<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['admin'])){
    header("Location: login_admin.php");
    exit;
}

// =======================
// HAPUS BUKU
// =======================
if(isset($_GET['hapus']) && is_numeric($_GET['hapus'])){
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM buku WHERE id_buku=$id");
    header("Location: buku.php");
    exit;
}

// =======================
// CARI BUKU
// =======================
$keyword = '';
$where = '';
if(isset($_GET['cari']) && $_GET['cari'] !== ''){
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    $where = "WHERE judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%'";
}

$result = mysqli_query($conn, "SELECT * FROM buku $where");

// =======================
// EDIT DATA
// =======================
$id_edit = 0; 
$judul=""; 
$penulis=""; 
$stok="";

if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
    $id_edit = intval($_GET['edit']);
    $data_edit = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku=$id_edit");
    if(mysqli_num_rows($data_edit) > 0){
        $row_edit = mysqli_fetch_assoc($data_edit);
        $judul = $row_edit['judul'];
        $penulis = $row_edit['penulis'];
        $stok = $row_edit['stok'];
    }
}

// =======================
// SIMPAN / UPDATE
// =======================
if(isset($_POST['simpan'])){
    $id = intval($_POST['id']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $stok = intval($_POST['stok']);

    if($id > 0){
        mysqli_query($conn, "UPDATE buku SET 
            judul='$judul',
            penulis='$penulis',
            stok=$stok
            WHERE id_buku=$id");
        echo "<script>alert('Buku berhasil diupdate');window.location='buku.php';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO buku (judul,penulis,stok) 
            VALUES ('$judul','$penulis',$stok)");
        echo "<script>alert('Buku berhasil ditambahkan');window.location='buku.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Data Buku</title>
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
a.button { padding:6px 12px; background:#1e40af; color:white; text-decoration:none; border-radius:4px; }
a.button:hover { background:#2563eb; }
form { margin-top:20px; background:white; padding:20px; border-radius:10px; }
form input { padding:8px; width:100%; margin:5px 0; border-radius:6px; border:1px solid #ccc; }
form button { padding:10px; background:#1e40af; color:white; border:none; cursor:pointer; border-radius:6px; }
form button:hover { background:#2563eb; }
.search-form { margin-top:10px; }
.search-form input { width:200px; display:inline-block; }
.search-form button { display:inline-block; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="dashboard_admin.php">Dashboard</a>
    <a href="buku.php">Data Buku</a>
    <a href="anggota.php">Data Anggota</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
<h1>Data Buku</h1>

<!-- FORM CARI -->
<form method="GET" class="search-form">
    <input type="text" name="cari" placeholder="Cari judul atau pengarang" value="<?php echo htmlspecialchars($keyword); ?>">
    <button type="submit">Cari</button>
</form>

<a href="#tambah" class="button">+ Tambah Buku</a>

<!-- TABEL DATA -->
<table>
<tr>
    <th>ID</th>
    <th>Judul</th>
    <th>Pengarang</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php 
if(mysqli_num_rows($result) > 0):
    while($row = mysqli_fetch_assoc($result)):
?>
<tr>
    <td><?php echo $row['id_buku']; ?></td>
    <td><?php echo htmlspecialchars($row['judul']); ?></td>
    <td><?php echo htmlspecialchars($row['penulis']); ?></td>
    <td><?php echo $row['stok']; ?></td>
    <td>
        <a href="buku.php?edit=<?php echo $row['id_buku']; ?>" class="button">Edit</a>
        <a href="buku.php?hapus=<?php echo $row['id_buku']; ?>" 
           class="button"
           onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
    </td>
</tr>
<?php 
    endwhile;
else:
?>
<tr><td colspan="5" style="text-align:center;">Tidak ada data buku</td></tr>
<?php endif; ?>
</table>

<!-- FORM TAMBAH / EDIT -->
<h2 id="tambah"><?php echo $id_edit ? "Edit Buku" : "Tambah Buku"; ?></h2>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $id_edit; ?>">
    <input type="text" name="judul" placeholder="Judul" value="<?php echo htmlspecialchars($judul); ?>" required>
    <input type="text" name="penulis" placeholder="Pengarang" value="<?php echo htmlspecialchars($penulis); ?>" required>
    <input type="number" name="stok" placeholder="Stok" value="<?php echo htmlspecialchars($stok); ?>" required>
    <button type="submit" name="simpan">
        <?php echo $id_edit ? "Update" : "Tambah"; ?>
    </button>
</form>

</div>
</body>
</html>