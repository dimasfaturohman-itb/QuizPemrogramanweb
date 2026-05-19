<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$stmt = mysqli_prepare($conn, "SELECT id_pendaftar FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$pendaftar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$dokumen = [];
if ($pendaftar) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM dokumen WHERE id_pendaftar=? ORDER BY id_dokumen DESC");
    mysqli_stmt_bind_param($stmt, "i", $pendaftar['id_pendaftar']);
    mysqli_stmt_execute($stmt);
    $dokumen = mysqli_stmt_get_result($stmt);
}

$page_title = 'Upload Dokumen';
$active_menu = 'dokumen';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Upload Dokumen</h1>
    <p class="text-muted mb-0">Upload dokumen dalam format PDF, JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Dokumen berhasil diupload.</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= e($_GET['error']); ?></div>
<?php endif; ?>

<?php if (!$pendaftar): ?>
    <div class="alert alert-warning">
        Isi biodata terlebih dahulu sebelum upload dokumen.
        <a href="biodata.php" class="alert-link">Isi biodata</a>
    </div>
<?php else: ?>
    <div class="card stat-card mb-4">
        <div class="card-body">
            <form action="proses_upload_dokumen.php" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Jenis Dokumen</label>
                    <select name="jenis_dokumen" class="form-select" required>
                        <option value="">Pilih dokumen</option>
                        <option value="KTP">KTP</option>
                        <option value="Kartu Keluarga">Kartu Keluarga</option>
                        <option value="Ijazah">Ijazah</option>
                        <option value="Pas Foto">Pas Foto</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">File</label>
                    <input type="file" name="file_dokumen" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <h2 class="h5 mb-3">Dokumen Saya</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($dokumen)): ?>
                            <tr>
                                <td><?= e($row['jenis_dokumen']); ?></td>
                                <td><a href="../uploads/dokumen/<?= e($row['nama_file']); ?>" target="_blank">Lihat file</a></td>
                                <td><span class="badge bg-secondary"><?= e($row['status_verifikasi']); ?></span></td>
                                <td><?= e($row['catatan'] ?? '-'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include '../layouts/footer.php'; ?>
