<?php
require_once 'config/database.php';

function formatRupiahPublic($number)
{
    if ($number === null || $number === '') {
        return '-';
    }
    return 'Rp ' . number_format((float) $number, 0, ',', '.');
}

$items = [];
$error_message = '';

try {
    $stmt = $pdo->query('SELECT id_barang, nama_barang, kategori, harga_jual, stok FROM Barang ORDER BY nama_barang ASC');
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Tersedia - KoperasiKu</title>
    <link rel="stylesheet" href="assets/css/public.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="assets/img/logo.svg" alt="KoperasiKu" class="logo-img">
            <span class="logo-text">Koperasi<span class="logo-highlight">Ku</span></span>
        </div>
        <nav>
            <a href="home.php">Home</a>
            <a href="admin.php" class="admin">Admin</a>
        </nav>
    </header>

    <main class="main-content">
        <h1 class="page-title">Barang <span>Tersedia</span></h1>

        <div class="content-card">
            <?php if (!empty($error_message)): ?>
                <p class="muted"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif (empty($items)): ?>
                <p class="muted">Belum ada data barang.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori'] ?? '-'); ?></td>
                                <td><?php echo formatRupiahPublic($row['harga_jual']); ?></td>
                                <td><?php echo htmlspecialchars((string) ($row['stok'] ?? 0)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>Contact | Social Media | Alamat | © 2025 KoperasiKu</p>
    </footer>
</body>

</html>
