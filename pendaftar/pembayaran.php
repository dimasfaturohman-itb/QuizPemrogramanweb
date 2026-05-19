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
if ($pendaftar) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM pembayaran WHERE id_pendaftar=? ORDER BY id_pembayaran DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $pendaftar['id_pendaftar']);
    mysqli_stmt_execute($stmt);
    $pembayaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$biaya_daftar_ulang = 1500000;
$page_title = 'Pembayaran';
$active_menu = 'pembayaran';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Pembayaran Daftar Ulang</h1>
    <p class="text-muted mb-0">Upload bukti pembayaran setelah dinyatakan diterima.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Bukti pembayaran berhasil dikirim. Silakan tunggu verifikasi admin.</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= e($_GET['error']); ?></div>
<?php endif; ?>

<?php if (!$pendaftar): ?>
    <div class="alert alert-warning">Isi biodata terlebih dahulu sebelum masuk tahap pembayaran.</div>
<?php elseif ($pendaftar['status_pendaftaran'] !== 'diterima'): ?>
    <div class="alert alert-info">
        Tahap pembayaran dibuka setelah status pendaftaran kamu <strong>diterima</strong>.
        Status saat ini: <strong><?= e($pendaftar['status_pendaftaran']); ?></strong>.
    </div>
<?php else: ?>
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card stat-card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Informasi Pembayaran</h2>
                    <dl class="row mb-0">
                        <dt class="col-5">Jumlah</dt>
                        <dd class="col-7"><?= e(rupiah($biaya_daftar_ulang)); ?></dd>
                        <dt class="col-5">Bank</dt>
                        <dd class="col-7">Bank Kampus</dd>
                        <dt class="col-5">No Rekening</dt>
                        <dd class="col-7">1234567890</dd>
                        <dt class="col-5">Atas Nama</dt>
                        <dd class="col-7">PMB Kampus</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card stat-card mb-3">
                <div class="card-body">
                    <h2 class="h5 mb-3">Upload Bukti Bayar</h2>
                    <form action="proses_pembayaran.php" method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" class="form-control" value="<?= e($pembayaran['jumlah_bayar'] ?? $biaya_daftar_ulang); ?>" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" class="form-control" value="<?= e($pembayaran['tanggal_bayar'] ?? date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bukti Bayar</label>
                            <input type="file" name="bukti_bayar" class="form-control" required>
                            <div class="form-text">Format PDF, JPG, JPEG, atau PNG. Maksimal 2 MB.</div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Status Pembayaran</h2>
                    <?php if (!$pembayaran): ?>
                        <p class="text-muted mb-0">Belum ada bukti pembayaran yang dikirim.</p>
                    <?php else: ?>
                        <?php
                        $badge = 'bg-warning text-dark';
                        if ($pembayaran['status_verifikasi'] === 'lunas') {
                            $badge = 'bg-success';
                        } elseif ($pembayaran['status_verifikasi'] === 'ditolak') {
                            $badge = 'bg-danger';
                        } elseif ($pembayaran['status_verifikasi'] === 'belum_bayar') {
                            $badge = 'bg-secondary';
                        }
                        ?>
                        <dl class="row mb-0">
                            <dt class="col-md-4">Status</dt>
                            <dd class="col-md-8"><span class="badge <?= e($badge); ?>"><?= e($pembayaran['status_verifikasi']); ?></span></dd>
                            <dt class="col-md-4">Jumlah</dt>
                            <dd class="col-md-8"><?= e(rupiah($pembayaran['jumlah_bayar'])); ?></dd>
                            <dt class="col-md-4">Tanggal</dt>
                            <dd class="col-md-8"><?= e($pembayaran['tanggal_bayar']); ?></dd>
                            <dt class="col-md-4">Bukti</dt>
                            <dd class="col-md-8"><a href="../uploads/pembayaran/<?= e($pembayaran['bukti_bayar']); ?>" target="_blank">Lihat bukti</a></dd>
                            <dt class="col-md-4">Catatan</dt>
                            <dd class="col-md-8"><?= e($pembayaran['catatan'] ?: '-'); ?></dd>
                        </dl>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include '../layouts/footer.php'; ?>
