<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$id_pembayaran = $_POST['id_pembayaran'] ?? 0;
$status_verifikasi = $_POST['status_verifikasi'] ?? 'menunggu';
$catatan = $_POST['catatan'] ?? '';
$status_allowed = ['menunggu', 'lunas', 'ditolak'];

if (!in_array($status_verifikasi, $status_allowed)) {
    $status_verifikasi = 'menunggu';
}

$stmt = mysqli_prepare($conn, "UPDATE pembayaran SET status_verifikasi=?, catatan=? WHERE id_pembayaran=?");
mysqli_stmt_bind_param($stmt, "ssi", $status_verifikasi, $catatan, $id_pembayaran);
mysqli_stmt_execute($stmt);

redirect('pembayaran.php?success=1');
