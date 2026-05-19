<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$id_dokumen = $_POST['id_dokumen'] ?? 0;
$status_verifikasi = $_POST['status_verifikasi'] ?? 'menunggu';
$catatan = $_POST['catatan'] ?? '';
$status_allowed = ['menunggu', 'diterima', 'ditolak'];

if (!in_array($status_verifikasi, $status_allowed)) {
    $status_verifikasi = 'menunggu';
}

$stmt = mysqli_prepare($conn, "UPDATE dokumen SET status_verifikasi=?, catatan=?, updated_at=NOW() WHERE id_dokumen=?");
mysqli_stmt_bind_param($stmt, "ssi", $status_verifikasi, $catatan, $id_dokumen);
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($conn, "SELECT id_pendaftar FROM dokumen WHERE id_dokumen=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_dokumen);
mysqli_stmt_execute($stmt);
$dokumen = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($dokumen) {
    $id_pendaftar = $dokumen['id_pendaftar'];

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM dokumen WHERE id_pendaftar=?");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $total_dokumen = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM dokumen WHERE id_pendaftar=? AND status_verifikasi='ditolak'");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $dokumen_ditolak = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM dokumen WHERE id_pendaftar=? AND status_verifikasi='diterima'");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $dokumen_diterima = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;

    $status_pendaftaran = 'menunggu';

    if ((int) $dokumen_ditolak > 0) {
        $status_pendaftaran = 'ditolak';
    } elseif ((int) $total_dokumen > 0 && (int) $total_dokumen === (int) $dokumen_diterima) {
        $status_pendaftaran = 'diterima';
    }

    $stmt = mysqli_prepare($conn, "UPDATE pendaftar SET status_pendaftaran=?, updated_at=NOW() WHERE id_pendaftar=?");
    mysqli_stmt_bind_param($stmt, "si", $status_pendaftaran, $id_pendaftar);
    mysqli_stmt_execute($stmt);
}

redirect('verifikasi_dokumen.php?success=1');
