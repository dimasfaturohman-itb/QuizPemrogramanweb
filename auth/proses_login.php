<?php
session_start();
require_once '../config/koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($data) {

    if (password_verify($password, $data['password'])) {

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {

            header("Location: ../admin/dashboard.php");
            exit;

        } else {

            header("Location: ../pendaftar/dashboard.php");
            exit;
        }

    } else {

        header("Location: login.php?error=Password salah");
        exit;
    }

} else {

    header("Location: login.php?error=Email tidak ditemukan");
    exit;
}
