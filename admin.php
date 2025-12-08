<?php
require_once 'config/database.php';

// Determine which table to display
$table = $_GET['table'] ?? 'anggota';
$allowed_tables = ['anggota', 'pengurus', 'simpanan', 'pinjaman', 'barang', 'pelatihan', 'peserta'];

if (!in_array($table, $allowed_tables)) {
    $table = 'anggota';
}

$data = [];
$error_message = '';
$page_title = '';
$table_headers = [];
$primary_key = '';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && isset($_GET['table'])) {
    try {
        $delete_table = $_GET['table'];
        $id = $_GET['id'];

        switch ($delete_table) {
            case 'anggota':
                $delete_query = "DELETE FROM Anggota WHERE id_anggota = ?";
                break;
            case 'pengurus':
                $delete_query = "DELETE FROM Pengurus WHERE id_pengurus = ?";
                break;
            case 'simpanan':
                $delete_query = "DELETE FROM Simpanan WHERE id_simpanan = ?";
                break;
            case 'pinjaman':
                $delete_query = "DELETE FROM Pinjaman WHERE id_pinjaman = ?";
                break;
            case 'barang':
                $delete_query = "DELETE FROM Barang WHERE id_barang = ?";
                break;
            case 'pelatihan':
                $delete_query = "DELETE FROM Pelatihan WHERE id_pelatihan = ?";
                break;
            case 'peserta':
                $delete_query = "DELETE FROM Peserta_pelatihan WHERE id_peserta = ?";
                break;
        }

        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->execute([$id]);
        header("Location: admin.php?table=" . $delete_table);
        exit();
    } catch (PDOException $e) {
        $error_message = "Error deleting data: " . $e->getMessage();
    }
}

// Fetch data based on selected table
try {
    switch ($table) {
        case 'anggota':
            $query = "SELECT * FROM Anggota ORDER BY id_anggota DESC";
            $page_title = "Data Anggota";
            $table_headers = ['No', 'Nama', 'Alamat', 'No HP', 'Aksi'];
            $primary_key = 'id_anggota';
            break;

        case 'pengurus':
            $query = "SELECT p.*, a.nama 
                      FROM Pengurus p 
                      INNER JOIN Anggota a ON p.id_anggota = a.id_anggota 
                      ORDER BY p.id_pengurus DESC";
            $page_title = "Data Pengurus";
            $table_headers = ['No', 'Nama', 'Jabatan', 'Periode', 'Aksi'];
            $primary_key = 'id_pengurus';
            break;

        case 'simpanan':
            $query = "SELECT s.*, a.nama 
                      FROM Simpanan s 
                      INNER JOIN Anggota a ON s.id_anggota = a.id_anggota 
                      ORDER BY s.tgl_simpan DESC";
            $page_title = "Data Simpanan";
            $table_headers = ['No', 'Nama Anggota', 'Jenis', 'Jumlah', 'Tanggal', 'Aksi'];
            $primary_key = 'id_simpanan';
            break;

        case 'pinjaman':
            $query = "SELECT p.*, a.nama 
                      FROM Pinjaman p 
                      INNER JOIN Anggota a ON p.id_anggota = a.id_anggota 
                      ORDER BY p.tgl_pinjam DESC";
            $page_title = "Data Pinjaman";
            $table_headers = ['No', 'Nama Anggota', 'Jumlah', 'Tanggal Pinjam', 'Status', 'Aksi'];
            $primary_key = 'id_pinjaman';
            break;

        case 'barang':
            $query = "SELECT * FROM Barang ORDER BY id_barang DESC";
            $page_title = "Data Barang";
            $table_headers = ['No', 'Nama Barang', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok', 'Aksi'];
            $primary_key = 'id_barang';
            break;

        case 'pelatihan':
            $query = "SELECT pel.*, n.nama as nama_narasumber 
                      FROM Pelatihan pel 
                      LEFT JOIN Narasumber n ON pel.id_narasumber = n.id_narasumber 
                      ORDER BY pel.tanggal DESC";
            $page_title = "Data Pelatihan";
            $table_headers = ['No', 'Judul', 'Tanggal', 'Tempat', 'Narasumber', 'Biaya', 'Aksi'];
            $primary_key = 'id_pelatihan';
            break;

        case 'peserta':
            $query = "SELECT pp.*, a.nama, pel.judul_pelatihan 
                      FROM Peserta_pelatihan pp 
                      INNER JOIN Anggota a ON pp.id_anggota = a.id_anggota 
                      INNER JOIN Pelatihan pel ON pp.id_pelatihan = pel.id_pelatihan 
                      ORDER BY pp.id_peserta DESC";
            $page_title = "Data Peserta Pelatihan";
            $table_headers = ['No', 'Nama', 'Pelatihan', 'Kehadiran', 'Sertifikat', 'Aksi'];
            $primary_key = 'id_peserta';
            break;
    }

    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $data = [];
}

// Format currency function
function formatRupiah($number)
{
    return "Rp " . number_format($number, 0, ',', '.');
}

// Format date function
function formatTanggal($date)
{
    if (empty($date))
        return '-';
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
    $d = date('d', $timestamp);
    $m = date('n', $timestamp);
    $y = date('Y', $timestamp);
    return $d . ' ' . $bulan[$m] . ' ' . $y;
}

// Get table display name
function getTableDisplayName($table)
{
    $names = [
        'anggota' => 'Anggota',
        'pengurus' => 'Pengurus',
        'simpanan' => 'Simpanan',
        'pinjaman' => 'Pinjaman',
        'barang' => 'Barang',
        'pelatihan' => 'Pelatihan',
        'peserta' => 'Peserta Pelatihan'
    ];
    return $names[$table] ?? 'Data';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - KoperasiKu</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <img src="assets/img/logo.svg" alt="KoperasiKu" class="logo-img">
                <span class="logo-text">Koperasi<span class="logo-highlight">Ku</span></span>
            </div>
            <div class="header-right">
                <div class="dropdown">
                    <button class="dropdown-toggle">
                        <?php echo getTableDisplayName($table); ?>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="admin.php?table=anggota">Anggota</a>
                        <a href="admin.php?table=pengurus">Pengurus</a>
                        <a href="admin.php?table=simpanan">Simpanan</a>
                        <a href="admin.php?table=pinjaman">Pinjaman</a>
                        <a href="admin.php?table=barang">Barang</a>
                        <a href="admin.php?table=pelatihan">Pelatihan</a>
                        <a href="admin.php?table=peserta">Peserta Pelatihan</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="page-title"><?php echo $page_title; ?></h1>

            <!-- Table Section -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php foreach ($table_headers as $header): ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="<?php echo count($table_headers); ?>"
                                    style="text-align: center; padding: 40px;">
                                    Tidak ada data
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($data as $row): ?>
                                <tr>
                                    <?php
                                    // Display data based on table type
                                    switch ($table) {
                                        case 'anggota':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['alamat'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['no_hp'] ?? '-') . "</td>";
                                            break;

                                        case 'pengurus':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['jabatan'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['periode'] ?? '-') . "</td>";
                                            break;

                                        case 'simpanan':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['jenis'] ?? '-') . "</td>";
                                            echo "<td>" . formatRupiah($row['jumlah']) . "</td>";
                                            echo "<td>" . formatTanggal($row['tgl_simpan']) . "</td>";
                                            break;

                                        case 'pinjaman':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . formatRupiah($row['jumlah']) . "</td>";
                                            echo "<td>" . formatTanggal($row['tgl_pinjam']) . "</td>";
                                            $status_class = (strtolower($row['status_pinjam']) == 'lunas') ? 'status-paid' : 'status-active';
                                            echo "<td><span class='status-badge {$status_class}'>" . ucfirst($row['status_pinjam']) . "</span></td>";
                                            break;

                                        case 'barang':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['kategori'] ?? '-') . "</td>";
                                            echo "<td>" . formatRupiah($row['harga_beli']) . "</td>";
                                            echo "<td>" . formatRupiah($row['harga_jual']) . "</td>";
                                            echo "<td>" . $row['stok'] . "</td>";
                                            break;

                                        case 'pelatihan':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['judul_pelatihan']) . "</td>";
                                            echo "<td>" . formatTanggal($row['tanggal']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['tempat'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_narasumber'] ?? '-') . "</td>";
                                            echo "<td>" . formatRupiah($row['biaya_pendaftaran']) . "</td>";
                                            break;

                                        case 'peserta':
                                            echo "<td>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['judul_pelatihan']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status_kehadiran'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['sertifikat'] ?? '-') . "</td>";
                                            break;
                                    }
                                    ?>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_data.php?table=<?php echo $table; ?>&id=<?php echo $row[$primary_key]; ?>"
                                                class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="admin.php?action=delete&table=<?php echo $table; ?>&id=<?php echo $row[$primary_key]; ?>"
                                                class="btn-delete" title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php $no++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Button -->
            <div class="add-button-container">
                <a href="tambah_data.php?table=<?php echo $table; ?>" class="btn-add-data">
                    Inputkan Data
                </a>
            </div>
        </main>
    </div>

    <script src="assets/js/admin.js"></script>
</body>

</html>
