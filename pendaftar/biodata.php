<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('pendaftar');

$id_user = current_user_id();
$stmt = mysqli_prepare($conn, "SELECT * FROM pendaftar WHERE id_user=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$biodata = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$prodi = mysqli_query($conn, "SELECT id_prodi, nama_prodi FROM prodi ORDER BY nama_prodi ASC");

$page_title = 'Biodata Pendaftar';
$active_menu = 'biodata';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Biodata Pendaftar</h1>
    <p class="text-muted mb-0">Isi data dengan benar sesuai dokumen resmi.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Biodata berhasil disimpan.</div>
<?php endif; ?>

<div class="card stat-card">
    <div class="card-body">
        <form action="proses_biodata.php" method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="<?= e($biodata['nik'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= e($biodata['no_hp'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="<?= e($biodata['tempat_lahir'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?= e($biodata['tanggal_lahir'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="Laki-laki" <?= ($biodata['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($biodata['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Agama</label>
                <input type="text" name="agama" class="form-control" value="<?= e($biodata['agama'] ?? ''); ?>" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= e($biodata['alamat'] ?? ''); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" class="form-control" value="<?= e($biodata['asal_sekolah'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jurusan Sekolah</label>
                <input type="text" name="jurusan_sekolah" class="form-control" value="<?= e($biodata['jurusan_sekolah'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Program Studi Pilihan</label>
                <select name="id_prodi" class="form-select" required>
                    <option value="">Pilih prodi</option>
                    <?php while ($row = mysqli_fetch_assoc($prodi)): ?>
                        <option value="<?= e($row['id_prodi']); ?>" <?= (string)($biodata['id_prodi'] ?? '') === (string)$row['id_prodi'] ? 'selected' : ''; ?>>
                            <?= e($row['nama_prodi']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Simpan Biodata</button>
            </div>
        </form>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
