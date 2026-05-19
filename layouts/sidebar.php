<?php
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? 'Pengguna';
$menu = [];

if ($role === 'admin') {
    $menu = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => '../admin/dashboard.php'],
        ['key' => 'pendaftar', 'label' => 'Data Pendaftar', 'url' => '../admin/pendaftar.php'],
        ['key' => 'verifikasi', 'label' => 'Verifikasi Dokumen', 'url' => '../admin/verifikasi_dokumen.php'],
        ['key' => 'pembayaran', 'label' => 'Verifikasi Pembayaran', 'url' => '../admin/pembayaran.php'],
        ['key' => 'ospek', 'label' => 'Data OSPEK', 'url' => '../admin/ospek.php'],
    ];
} else {
    $menu = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => '../pendaftar/dashboard.php'],
        ['key' => 'biodata', 'label' => 'Biodata', 'url' => '../pendaftar/biodata.php'],
        ['key' => 'dokumen', 'label' => 'Upload Dokumen', 'url' => '../pendaftar/dokumen.php'],
        ['key' => 'pembayaran', 'label' => 'Pembayaran', 'url' => '../pendaftar/pembayaran.php'],
        ['key' => 'ospek', 'label' => 'OSPEK', 'url' => '../pendaftar/ospek.php'],
    ];
}
?>
<aside class="sidebar">
    <div class="brand">PMB Kampus</div>
    <div class="user-box">
        <div class="fw-semibold"><?= e($nama); ?></div>
        <small><?= e(ucfirst($role)); ?></small>
    </div>
    <nav class="nav flex-column gap-1">
        <?php foreach ($menu as $item): ?>
            <a class="nav-link <?= $active_menu === $item['key'] ? 'active' : ''; ?>" href="<?= $item['url']; ?>">
                <?= e($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <a class="btn btn-outline-light btn-sm mt-auto" href="../auth/logout.php">Logout</a>
</aside>
<main class="content">
