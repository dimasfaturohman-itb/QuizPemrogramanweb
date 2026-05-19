<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$nik = $_POST['nik'];
$tempat_lahir = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$agama = $_POST['agama'];
$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];
$asal_sekolah = $_POST['asal_sekolah'];
$jurusan_sekolah = $_POST['jurusan_sekolah'];
$id_prodi = $_POST['id_prodi'];

$stmt = mysqli_prepare($conn, "SELECT id_pendaftar FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($data) {
    $stmt = mysqli_prepare($conn, "UPDATE pendaftar SET nik=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?, agama=?, alamat=?, no_hp=?, asal_sekolah=?, jurusan_sekolah=?, id_prodi=?, updated_at=NOW() WHERE id_user=?");
    mysqli_stmt_bind_param($stmt, "sssssssssii", $nik, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $no_hp, $asal_sekolah, $jurusan_sekolah, $id_prodi, $id_user);
} else {
    $status = 'menunggu';
    $stmt = mysqli_prepare($conn, "INSERT INTO pendaftar (id_user, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_hp, asal_sekolah, jurusan_sekolah, id_prodi, status_pendaftaran, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    mysqli_stmt_bind_param($stmt, "isssssssssis", $id_user, $nik, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $no_hp, $asal_sekolah, $jurusan_sekolah, $id_prodi, $status);
}

mysqli_stmt_execute($stmt);
redirect('biodata.php?success=1');
