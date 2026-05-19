<?php
require_once '../config/koneksi.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);

$stmt = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$cek = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($cek) > 0) {

    header("Location: register.php?error=Email sudah digunakan");
    exit;
}

$role = 'pendaftar';
$stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $password, $role);
$query = mysqli_stmt_execute($stmt);

if ($query) {

    header("Location: login.php?success=Register berhasil, silakan login");
    exit;

} else {

    echo "Register gagal!";
}
