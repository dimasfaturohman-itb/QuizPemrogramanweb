<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$query = mysqli_query($conn, "
    SELECT pb.*, p.id_pendaftar, p.status_pendaftaran, u.nama, u.email
    FROM pembayaran pb
    JOIN pendaftar p ON p.id_pendaftar = pb.id_pendaftar
    JOIN users u ON u.id_user = p.id_user
    ORDER BY pb.id_pembayaran DESC
");

$page_title = 'Verifikasi Pembayaran';
$active_menu = 'pembayaran';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Verifikasi Pembayaran</h1>
    <p class="text-muted mb-0">Periksa bukti bayar pendaftar yang sudah diterima.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Status pembayaran berhasil diperbarui.</div>
<?php endif; ?>

<div class="card stat-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Pendaftar</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <?php
                        $badge = 'bg-warning text-dark';
                        if ($row['status_verifikasi'] === 'lunas') {
                            $badge = 'bg-success';
                        } elseif ($row['status_verifikasi'] === 'ditolak') {
                            $badge = 'bg-danger';
                        } elseif ($row['status_verifikasi'] === 'belum_bayar') {
                            $badge = 'bg-secondary';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= e($row['nama']); ?></div>
                                <small class="text-muted"><?= e($row['email']); ?></small>
                            </td>
                            <td><?= e(rupiah($row['jumlah_bayar'])); ?></td>
                            <td><?= e($row['tanggal_bayar']); ?></td>
                            <td>
                                <?php if (!empty($row['bukti_bayar'])): ?>
                                    <a href="../uploads/pembayaran/<?= e($row['bukti_bayar']); ?>" target="_blank">Lihat bukti</a>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= e($badge); ?>"><?= e($row['status_verifikasi']); ?></span>
                                <?php if (!empty($row['catatan'])): ?>
                                    <div class="small text-muted mt-1"><?= e($row['catatan']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="min-width: 330px;">
                                <form action="proses_pembayaran.php" method="POST" class="row g-2">
                                    <input type="hidden" name="id_pembayaran" value="<?= e($row['id_pembayaran']); ?>">
                                    <div class="col-md-5">
                                        <select name="status_verifikasi" class="form-select form-select-sm" required>
                                            <option value="menunggu" <?= $row['status_verifikasi'] === 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                            <option value="lunas" <?= $row['status_verifikasi'] === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                                            <option value="ditolak" <?= $row['status_verifikasi'] === 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan" value="<?= e($row['catatan']); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
