<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$id_pendaftar = $_POST['id_pendaftar'] ?? 0;
$tanggal_ospek = $_POST['tanggal_ospek'] ?? null;
$lokasi = $_POST['lokasi'] ?? '';
$kelompok = $_POST['kelompok'] ?? '';
$mentor = $_POST['mentor'] ?? '';

$stmt = mysqli_prepare($conn, "
    SELECT id_pembayaran
    FROM pembayaran
    WHERE id_pendaftar=? AND status_verifikasi='lunas'
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
mysqli_stmt_execute($stmt);
$pembayaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$pembayaran) {
    redirect('ospek.php');
}

$stmt = mysqli_prepare($conn, "SELECT id_ospek FROM ospek WHERE id_pendaftar=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
mysqli_stmt_execute($stmt);
$ospek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($ospek) {
    $stmt = mysqli_prepare($conn, "UPDATE ospek SET tanggal_ospek=?, lokasi=?, kelompok=?, mentor=? WHERE id_pendaftar=?");
    mysqli_stmt_bind_param($stmt, "ssssi", $tanggal_ospek, $lokasi, $kelompok, $mentor, $id_pendaftar);
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO ospek (id_pendaftar, tanggal_ospek, lokasi, kelompok, mentor) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $id_pendaftar, $tanggal_ospek, $lokasi, $kelompok, $mentor);
}

mysqli_stmt_execute($stmt);
redirect('ospek.php?success=1');
