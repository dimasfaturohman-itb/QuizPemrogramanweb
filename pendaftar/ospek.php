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

$pembayaran = null;
$ospek = null;

if ($pendaftar) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM pembayaran WHERE id_pendaftar=? ORDER BY id_pembayaran DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $pendaftar['id_pendaftar']);
    mysqli_stmt_execute($stmt);
    $pembayaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($conn, "SELECT * FROM ospek WHERE id_pendaftar=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $pendaftar['id_pendaftar']);
    mysqli_stmt_execute($stmt);
    $ospek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$page_title = 'Informasi OSPEK';
$active_menu = 'ospek';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Informasi OSPEK</h1>
    <p class="text-muted mb-0">Informasi kegiatan pengenalan kampus untuk mahasiswa baru.</p>
</div>

<?php if (!$pendaftar): ?>
    <div class="alert alert-warning">Isi biodata terlebih dahulu sebelum masuk tahap OSPEK.</div>
<?php elseif (($pembayaran['status_verifikasi'] ?? '') !== 'lunas'): ?>
    <div class="alert alert-info">
        Informasi OSPEK dibuka setelah pembayaran daftar ulang kamu berstatus <strong>lunas</strong>.
        Status pembayaran saat ini: <strong><?= e($pembayaran['status_verifikasi'] ?? 'belum bayar'); ?></strong>.
    </div>
<?php elseif (!$ospek): ?>
    <div class="alert alert-warning">
        Pembayaran kamu sudah lunas. Jadwal OSPEK belum diatur oleh admin.
    </div>
<?php else: ?>
    <div class="card stat-card">
        <div class="card-body">
            <h2 class="h5 mb-3">Jadwal OSPEK Kamu</h2>
            <dl class="row mb-0">
                <dt class="col-md-3">Tanggal</dt>
                <dd class="col-md-9"><?= e($ospek['tanggal_ospek']); ?></dd>

                <dt class="col-md-3">Lokasi</dt>
                <dd class="col-md-9"><?= e($ospek['lokasi']); ?></dd>

                <dt class="col-md-3">Kelompok</dt>
                <dd class="col-md-9"><?= e($ospek['kelompok']); ?></dd>

                <dt class="col-md-3">Mentor</dt>
                <dd class="col-md-9"><?= e($ospek['mentor']); ?></dd>
            </dl>
        </div>
    </div>
<?php endif; ?>

<?php include '../layouts/footer.php'; ?>
