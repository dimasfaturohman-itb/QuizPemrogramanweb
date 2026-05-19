<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$id_pendaftar = $_GET['id'] ?? 0;
$stmt = mysqli_prepare($conn, "
    SELECT p.*, u.nama, u.email, pr.nama_prodi
    FROM pendaftar p
    JOIN users u ON u.id_user = p.id_user
    LEFT JOIN prodi pr ON pr.id_prodi = p.id_prodi
    WHERE p.id_pendaftar=?
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
mysqli_stmt_execute($stmt);
$pendaftar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$pendaftar) {
    redirect('pendaftar.php');
}

$badge = 'bg-warning text-dark';
if ($pendaftar['status_pendaftaran'] === 'diterima') {
    $badge = 'bg-success';
} elseif ($pendaftar['status_pendaftaran'] === 'ditolak') {
    $badge = 'bg-danger';
}

$page_title = 'Detail Pendaftar';
$active_menu = 'pendaftar';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Detail Pendaftar</h1>
    <p class="text-muted mb-0"><?= e($pendaftar['nama']); ?> - <?= e($pendaftar['email']); ?></p>
</div>

<div class="card stat-card">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-md-3">NIK</dt><dd class="col-md-9"><?= e($pendaftar['nik']); ?></dd>
            <dt class="col-md-3">TTL</dt><dd class="col-md-9"><?= e($pendaftar['tempat_lahir']); ?>, <?= e($pendaftar['tanggal_lahir']); ?></dd>
            <dt class="col-md-3">Jenis Kelamin</dt><dd class="col-md-9"><?= e($pendaftar['jenis_kelamin']); ?></dd>
            <dt class="col-md-3">Agama</dt><dd class="col-md-9"><?= e($pendaftar['agama']); ?></dd>
            <dt class="col-md-3">Alamat</dt><dd class="col-md-9"><?= e($pendaftar['alamat']); ?></dd>
            <dt class="col-md-3">No HP</dt><dd class="col-md-9"><?= e($pendaftar['no_hp']); ?></dd>
            <dt class="col-md-3">Asal Sekolah</dt><dd class="col-md-9"><?= e($pendaftar['asal_sekolah']); ?></dd>
            <dt class="col-md-3">Jurusan Sekolah</dt><dd class="col-md-9"><?= e($pendaftar['jurusan_sekolah']); ?></dd>
            <dt class="col-md-3">Prodi</dt><dd class="col-md-9"><?= e($pendaftar['nama_prodi'] ?? '-'); ?></dd>
            <dt class="col-md-3">Status</dt><dd class="col-md-9"><span class="badge <?= e($badge); ?>"><?= e($pendaftar['status_pendaftaran']); ?></span></dd>
        </dl>
        <a class="btn btn-outline-secondary mt-3" href="pendaftar.php">Kembali</a>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
