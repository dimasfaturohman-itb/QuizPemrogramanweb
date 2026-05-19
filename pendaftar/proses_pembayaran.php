<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$jumlah_bayar = $_POST['jumlah_bayar'] ?? 0;
$tanggal_bayar = $_POST['tanggal_bayar'] ?? date('Y-m-d');

$stmt = mysqli_prepare($conn, "SELECT id_pendaftar, status_pendaftaran FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$pendaftar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$pendaftar || $pendaftar['status_pendaftaran'] !== 'diterima') {
    redirect('pembayaran.php?error=Tahap pembayaran belum dibuka');
}

if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] !== UPLOAD_ERR_OK) {
    redirect('pembayaran.php?error=File gagal diupload');
}

$file = $_FILES['bukti_bayar'];
$max_size = 2 * 1024 * 1024;
$allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($file['size'] > $max_size) {
    redirect('pembayaran.php?error=Ukuran file maksimal 2 MB');
}

if (!in_array($ext, $allowed_ext)) {
    redirect('pembayaran.php?error=Format file tidak diizinkan');
}

$new_name = time() . '_' . $pendaftar['id_pendaftar'] . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
$destination = payment_upload_path($new_name);

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect('pembayaran.php?error=Gagal menyimpan file');
}

$stmt = mysqli_prepare($conn, "SELECT id_pembayaran FROM pembayaran WHERE id_pendaftar=? ORDER BY id_pembayaran DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $pendaftar['id_pendaftar']);
mysqli_stmt_execute($stmt);
$pembayaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$status = 'menunggu';
$catatan = '';

if ($pembayaran) {
    $stmt = mysqli_prepare($conn, "UPDATE pembayaran SET jumlah_bayar=?, tanggal_bayar=?, bukti_bayar=?, status_verifikasi=?, catatan=? WHERE id_pembayaran=?");
    mysqli_stmt_bind_param($stmt, "dssssi", $jumlah_bayar, $tanggal_bayar, $new_name, $status, $catatan, $pembayaran['id_pembayaran']);
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO pembayaran (id_pendaftar, jumlah_bayar, tanggal_bayar, bukti_bayar, status_verifikasi, catatan) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "idssss", $pendaftar['id_pendaftar'], $jumlah_bayar, $tanggal_bayar, $new_name, $status, $catatan);
}

mysqli_stmt_execute($stmt);
redirect('pembayaran.php?success=1');
