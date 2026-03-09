<?php
session_start();
include "koneksi.php";

// Cek admin login
if(!isset($_SESSION['admin'])){
    header("Location: login_admin.php");
    exit;
}

// =======================
// HAPUS ANGGOTA
// =======================
if(isset($_GET['hapus']) && is_numeric($_GET['hapus'])){
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM anggota WHERE id_anggota=$id");
    header("Location: anggota.php");
    exit;
}

// =======================
// AMBIL DATA ANGGOTA
// =======================
$result = mysqli_query($conn, "SELECT * FROM anggota");

// =======================
// INISIALISASI FORM
// =======================
$id_edit = 0; 
$username = ""; 
$nama = ""; 
$email = ""; 
$password = "";

// =======================
// EDIT DATA
// =======================
if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
    $id_edit = intval($_GET['edit']);
    $data_edit = mysqli_query($conn, "SELECT * FROM anggota WHERE id_anggota=$id_edit");
    if(mysqli_num_rows($data_edit) > 0){
        $row_edit = mysqli_fetch_assoc($data_edit);
        $username = $row_edit['username'];
        $nama = $row_edit['nama'];
    }
}

// =======================
// SIMPAN / UPDATE
// =======================
if(isset($_POST['simpan'])){
    $id = intval($_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if($id > 0){ // update
        $sql = "UPDATE anggota SET username='$username', nama='$nama'";
        if(!empty($password)){
            $sql .= ", password='$password'";
        }
        $sql .= " WHERE id_anggota=$id";
        mysqli_query($conn, $sql);
    } else { // tambah
        mysqli_query($conn, "INSERT INTO anggota (username,password,nama) 
            VALUES ('$username','$password','$nama')");
    }

    header("Location: anggota.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Kelola Anggota</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; }
.container { width:90%; margin:30px auto; }
h1 { color:#1e3a8a; }
table { width:100%; border-collapse: collapse; background:white; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#1e40af; color:white; }
a.button { padding:6px 12px; background:#1e40af; color:white; text-decoration:none; border-radius:4px; }
a.button:hover { background:#2563eb; }
form { margin-top:20px; background:white; padding:20px; border-radius:10px; }
form input { padding:8px; width:100%; margin:5px 0; border-radius:6px; border:1px solid #ccc; }
form button { padding:10px; background:#1e40af; color:white; border:none; cursor:pointer; border-radius:6px; }
form button:hover { background:#2563eb; }
</style>
</head>
<body>

<div class="container">
<h1>Kelola Anggota</h1>
<a href="#tambah" class="button">+ Tambah Anggota</a>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Nama Lengkap</th>
    <th>Email</th>
    <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['id_anggota']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['nama']); ?></td>
        <td>-</td>
        <td>
            <a href="anggota.php?edit=<?php echo $row['id_anggota']; ?>" class="button">Edit</a>
            <a href="anggota.php?hapus=<?php echo $row['id_anggota']; ?>" class="button" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="5" style="text-align:center;">Tidak ada data anggota</td></tr>
<?php endif; ?>
</table>

<h2 id="tambah"><?php echo $id_edit ? "Edit Anggota" : "Tambah Anggota"; ?></h2>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $id_edit; ?>">
    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama); ?>" required>
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password <?php echo $id_edit ? '(Kosongkan jika tidak diubah)' : ''; ?>">
    <button type="submit" name="simpan">
        <?php echo $id_edit ? "Update" : "Tambah"; ?>
    </button>
</form>

</div>
</body>
</html>