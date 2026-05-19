<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
require_once '../config/session.php';
require_role('admin');

$query = mysqli_query($conn, "
    SELECT p.*, u.nama, u.email, pr.nama_prodi
    FROM pendaftar p
    JOIN users u ON u.id_user = p.id_user
    LEFT JOIN prodi pr ON pr.id_prodi = p.id_prodi
    ORDER BY p.id_pendaftar DESC
");

$page_title = 'Data Pendaftar';
$active_menu = 'pendaftar';
include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Data Pendaftar</h1>
        <p class="text-muted mb-0">Daftar calon mahasiswa yang sudah mengisi biodata.</p>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <?php
                        $badge = 'bg-warning text-dark';
                        if ($row['status_pendaftaran'] === 'diterima') {
                            $badge = 'bg-success';
                        } elseif ($row['status_pendaftaran'] === 'ditolak') {
                            $badge = 'bg-danger';
                        }
                        ?>
                        <tr>
                            <td><?= e($row['nama']); ?></td>
                            <td><?= e($row['email']); ?></td>
                            <td><?= e($row['no_hp']); ?></td>
                            <td><?= e($row['nama_prodi'] ?? '-'); ?></td>
                            <td><span class="badge <?= e($badge); ?>"><?= e($row['status_pendaftaran']); ?></span></td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="pendaftar_detail.php?id=<?= e($row['id_pendaftar']); ?>">Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
