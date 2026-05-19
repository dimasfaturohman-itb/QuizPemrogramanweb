<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$stmt = mysqli_prepare($conn, "SELECT * FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$pendaftar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$id_pendaftar = $pendaftar['id_pendaftar'] ?? 0;
$jumlah_dokumen = 0;
$pembayaran = null;
$ospek = null;
$status_text = $pendaftar['status_pendaftaran'] ?? 'belum lengkap';
$status_class = 'bg-secondary';

if ($id_pendaftar) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM dokumen WHERE id_pendaftar=?");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $jumlah_dokumen = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;

    $stmt = mysqli_prepare($conn, "SELECT * FROM pembayaran WHERE id_pendaftar=? ORDER BY id_pembayaran DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $pembayaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($conn, "SELECT * FROM ospek WHERE id_pendaftar=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt);
    $ospek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

if ($status_text === 'diterima') {
    $status_class = 'bg-success';
} elseif ($status_text === 'ditolak') {
    $status_class = 'bg-danger';
} elseif ($status_text === 'menunggu') {
    $status_class = 'bg-warning text-dark';
}

$page_title = 'Dashboard Pendaftar';
$active_menu = 'dashboard';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Dashboard Pendaftar</h1>
    <p class="text-muted mb-0">Selamat datang, <?= e(current_user_name()); ?>.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Status Biodata</div>
                <div class="fs-5 fw-bold"><?= $pendaftar ? 'Sudah diisi' : 'Belum diisi'; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Dokumen Terupload</div>
                <div class="fs-5 fw-bold"><?= e($jumlah_dokumen); ?> dokumen</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Status Pendaftaran</div>
                <div class="fs-5 fw-bold"><span class="badge <?= e($status_class); ?>"><?= e($status_text); ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Status Pembayaran</div>
                <div class="fs-5 fw-bold"><?= e($pembayaran['status_verifikasi'] ?? 'belum bayar'); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Status OSPEK</div>
                <div class="fs-5 fw-bold"><?= $ospek ? 'Terjadwal' : 'Belum terjadwal'; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <h2 class="h5">Alur PMB</h2>
        <p class="text-muted">Lengkapi biodata, upload dokumen, tunggu hasil, lakukan pembayaran daftar ulang, lalu ikuti OSPEK.</p>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="biodata.php">Isi Biodata</a>
            <a class="btn btn-outline-primary" href="dokumen.php">Upload Dokumen</a>
            <a class="btn btn-outline-primary" href="pembayaran.php">Pembayaran</a>
            <a class="btn btn-outline-primary" href="ospek.php">OSPEK</a>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
