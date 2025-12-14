<?php
require_once 'config/database.php';

function formatTanggalPublic($date)
{
    if (empty($date)) {
        return '-';
    }
    $bulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agt',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return htmlspecialchars($date);
    }
    $d = date('d', $timestamp);
    $m = (int) date('n', $timestamp);
    $y = date('Y', $timestamp);
    return $d . ' ' . ($bulan[$m] ?? $m) . ' ' . $y;
}

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
    $query = "SELECT pel.id_pelatihan, pel.judul_pelatihan, pel.tanggal, pel.tempat, pel.biaya_pendaftaran, n.nama as nama_narasumber
              FROM Pelatihan pel
              LEFT JOIN Narasumber n ON pel.id_narasumber = n.id_narasumber
              ORDER BY pel.tanggal DESC, pel.id_pelatihan DESC";
    $stmt = $pdo->query($query);
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
    <title>Info Pelatihan - KoperasiKu</title>
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
        <h1 class="page-title">Info <span>Pelatihan</span></h1>

        <div class="content-card">
            <p class="muted" style="margin-bottom: 14px;">Untuk pendaftaran pelatihan, silakan hubungi admin.</p>

            <?php if (!empty($error_message)): ?>
                <p class="muted"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif (empty($items)): ?>
                <p class="muted">Belum ada data pelatihan.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Tempat</th>
                            <th>Narasumber</th>
                            <th>Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['judul_pelatihan'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars(formatTanggalPublic($row['tanggal'] ?? '')); ?></td>
                                <td><?php echo htmlspecialchars($row['tempat'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_narasumber'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars(formatRupiahPublic($row['biaya_pendaftaran'] ?? '')); ?></td>
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
