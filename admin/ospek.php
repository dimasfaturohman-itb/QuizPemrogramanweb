<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$query = mysqli_query($conn, "
    SELECT p.id_pendaftar, u.nama, u.email, pb.status_verifikasi AS status_pembayaran,
           o.id_ospek, o.tanggal_ospek, o.lokasi, o.kelompok, o.mentor
    FROM pendaftar p
    JOIN users u ON u.id_user = p.id_user
    JOIN pembayaran pb ON pb.id_pendaftar = p.id_pendaftar
    LEFT JOIN ospek o ON o.id_pendaftar = p.id_pendaftar
    WHERE pb.status_verifikasi = 'lunas'
    ORDER BY o.tanggal_ospek IS NULL DESC, o.tanggal_ospek ASC, u.nama ASC
");

$page_title = 'Data OSPEK';
$active_menu = 'ospek';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="mb-4">
    <h1 class="h3 mb-1">Data OSPEK</h1>
    <p class="text-muted mb-0">Atur jadwal OSPEK untuk pendaftar yang sudah lunas pembayaran.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data OSPEK berhasil disimpan.</div>
<?php endif; ?>

<div class="card stat-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Pendaftar</th>
                        <th>Status Bayar</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Kelompok</th>
                        <th>Mentor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= e($row['nama']); ?></div>
                                <small class="text-muted"><?= e($row['email']); ?></small>
                            </td>
                            <td><span class="badge bg-success"><?= e($row['status_pembayaran']); ?></span></td>
                            <form action="proses_ospek.php" method="POST">
                                <input type="hidden" name="id_pendaftar" value="<?= e($row['id_pendaftar']); ?>">
                                <td><input type="date" name="tanggal_ospek" class="form-control form-control-sm" value="<?= e($row['tanggal_ospek']); ?>" required></td>
                                <td><input type="text" name="lokasi" class="form-control form-control-sm" value="<?= e($row['lokasi']); ?>" required></td>
                                <td><input type="text" name="kelompok" class="form-control form-control-sm" value="<?= e($row['kelompok']); ?>" required></td>
                                <td><input type="text" name="mentor" class="form-control form-control-sm" value="<?= e($row['mentor']); ?>" required></td>
                                <td><button type="submit" class="btn btn-sm btn-primary">Simpan</button></td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
