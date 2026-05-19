<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$query = mysqli_query($conn, "
    SELECT d.*, p.id_pendaftar, u.nama, u.email
    FROM dokumen d
    JOIN pendaftar p ON p.id_pendaftar = d.id_pendaftar
    JOIN users u ON u.id_user = p.id_user
    ORDER BY d.id_dokumen DESC
");

$page_title = 'Verifikasi Dokumen';
$active_menu = 'verifikasi';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Verifikasi Dokumen</h1>
    <p class="text-muted mb-0">Periksa dokumen pendaftar lalu pilih status verifikasi.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Status dokumen berhasil diperbarui.</div>
<?php endif; ?>

<div class="card stat-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Pendaftar</th>
                        <th>Jenis Dokumen</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <?php
                        $badge = 'bg-warning text-dark';
                        if ($row['status_verifikasi'] === 'diterima') {
                            $badge = 'bg-success';
                        } elseif ($row['status_verifikasi'] === 'ditolak') {
                            $badge = 'bg-danger';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= e($row['nama']); ?></div>
                                <small class="text-muted"><?= e($row['email']); ?></small>
                            </td>
                            <td><?= e($row['jenis_dokumen']); ?></td>
                            <td><a href="../uploads/dokumen/<?= e($row['nama_file']); ?>" target="_blank">Lihat file</a></td>
                            <td>
                                <span class="badge <?= e($badge); ?>"><?= e($row['status_verifikasi']); ?></span>
                                <?php if (!empty($row['catatan'])): ?>
                                    <div class="small text-muted mt-1"><?= e($row['catatan']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="min-width: 330px;">
                                <form action="proses_verifikasi.php" method="POST" class="row g-2">
                                    <input type="hidden" name="id_dokumen" value="<?= e($row['id_dokumen']); ?>">
                                    <div class="col-md-5">
                                        <select name="status_verifikasi" class="form-select form-select-sm" required>
                                            <option value="menunggu" <?= $row['status_verifikasi'] === 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                            <option value="diterima" <?= $row['status_verifikasi'] === 'diterima' ? 'selected' : ''; ?>>Diterima</option>
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
