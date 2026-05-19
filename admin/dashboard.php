<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$total_pendaftar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pendaftar"))['total'] ?? 0;
$total_dokumen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM dokumen"))['total'] ?? 0;
$dokumen_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM dokumen WHERE status_verifikasi='menunggu'"))['total'] ?? 0;
$diterima = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pendaftar WHERE status_pendaftaran='diterima'"))['total'] ?? 0;
$ditolak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pendaftar WHERE status_pendaftaran='ditolak'"))['total'] ?? 0;
$pembayaran_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran WHERE status_verifikasi='menunggu'"))['total'] ?? 0;
$pembayaran_lunas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran WHERE status_verifikasi='lunas'"))['total'] ?? 0;
$total_ospek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM ospek"))['total'] ?? 0;

$page_title = 'Dashboard Admin';
$active_menu = 'dashboard';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Selamat datang, <?= e(current_user_name()); ?>.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Pendaftar</div>
                <div class="fs-3 fw-bold"><?= e($total_pendaftar); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Diterima</div>
                <div class="fs-3 fw-bold"><?= e($diterima); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Ditolak</div>
                <div class="fs-3 fw-bold"><?= e($ditolak); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Dokumen Menunggu</div>
                <div class="fs-3 fw-bold"><?= e($dokumen_menunggu); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Bayar Menunggu</div>
                <div class="fs-3 fw-bold"><?= e($pembayaran_menunggu); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">OSPEK</div>
                <div class="fs-3 fw-bold"><?= e($total_ospek); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <h2 class="h5">Menu Cepat</h2>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="pendaftar.php">Kelola Pendaftar</a>
            <a class="btn btn-outline-primary" href="verifikasi_dokumen.php">Verifikasi Dokumen</a>
            <a class="btn btn-outline-primary" href="pembayaran.php">Verifikasi Pembayaran</a>
            <a class="btn btn-outline-primary" href="ospek.php">Atur OSPEK</a>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
