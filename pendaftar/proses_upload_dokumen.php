<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$jenis_dokumen = $_POST['jenis_dokumen'] ?? '';

$stmt = mysqli_prepare($conn, "SELECT id_pendaftar FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$pendaftar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$pendaftar) {
    redirect('dokumen.php?error=Isi biodata terlebih dahulu');
}

if (!isset($_FILES['file_dokumen']) || $_FILES['file_dokumen']['error'] !== UPLOAD_ERR_OK) {
    redirect('dokumen.php?error=File gagal diupload');
}

$file = $_FILES['file_dokumen'];
$max_size = 2 * 1024 * 1024;
$allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($file['size'] > $max_size) {
    redirect('dokumen.php?error=Ukuran file maksimal 2 MB');
}

if (!in_array($ext, $allowed_ext)) {
    redirect('dokumen.php?error=Format file tidak diizinkan');
}

$new_name = time() . '_' . $pendaftar['id_pendaftar'] . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
$destination = upload_path($new_name);

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect('dokumen.php?error=Gagal menyimpan file');
}

$status = 'menunggu';
$catatan = '';
$stmt = mysqli_prepare($conn, "INSERT INTO dokumen (id_pendaftar, jenis_dokumen, nama_file, status_verifikasi, catatan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
mysqli_stmt_bind_param($stmt, "issss", $pendaftar['id_pendaftar'], $jenis_dokumen, $new_name, $status, $catatan);
mysqli_stmt_execute($stmt);

redirect('dokumen.php?success=1');
