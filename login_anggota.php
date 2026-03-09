<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Anggota</title>
<style>
body { font-family: Arial; background: #1573c0; display:flex; justify-content:center; align-items:center; height:100vh; }
.login-box { background: white; padding:30px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2); width: 350px; }
.login-box h2 { text-align:center; margin-bottom:20px; color:#1e3a8a; }
.login-box input, .login-box button { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
.login-box button { background:#1e40af; color:white; border:none; cursor:pointer; font-weight:bold; }
.login-box button:hover { background:#2563eb; }
.login-box a { display:block; text-align:center; margin-top:10px; color:#1e40af; text-decoration:none; }
.login-box a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="login-box">
<h2>Login Anggota</h2>
<form method="POST" action="proses_anggota.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
<br>
<a href="login_admin.php">Login sebagai Admin</a>
</div>

</body>
</html>