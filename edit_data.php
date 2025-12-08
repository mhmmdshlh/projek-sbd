<?php
require_once 'config/database.php';

$table = $_GET['table'] ?? 'anggota';
$id = $_GET['id'] ?? 0;

$allowed_tables = ['anggota', 'pengurus', 'simpanan', 'pinjaman', 'barang', 'pelatihan', 'peserta'];

if (!in_array($table, $allowed_tables) || !$id) {
    header("Location: admin.php");
    exit();
}

// Get primary key column name for each table
function getPrimaryKey($table) {
    $keys = [
        'anggota' => 'id_anggota',
        'pengurus' => 'id_pengurus',
        'simpanan' => 'id_simpanan',
        'pinjaman' => 'id_pinjaman',
        'barang' => 'id_barang',
        'pelatihan' => 'id_pelatihan',
        'peserta' => 'id_peserta'
    ];
    return $keys[$table] ?? 'id';
}

function getTableTitle($table) {
    $titles = [
        'anggota' => 'Anggota',
        'pengurus' => 'Pengurus',
        'simpanan' => 'Simpanan',
        'pinjaman' => 'Pinjaman',
        'barang' => 'Barang',
        'pelatihan' => 'Pelatihan',
        'peserta' => 'Peserta Pelatihan'
    ];
    return $titles[$table] ?? 'Data';
}

$primary_key = getPrimaryKey($table);

// Fetch existing data
try {
    $query = "SELECT * FROM " . ucfirst($table == 'peserta' ? 'Peserta_pelatihan' : $table) . " WHERE $primary_key = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    
    if (!$data) {
        header("Location: admin.php?table=" . $table);
        exit();
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch related data for dropdowns
$data_anggota = [];
$data_pelatihan = [];
$data_narasumber = [];

if (in_array($table, ['pengurus', 'simpanan', 'pinjaman', 'peserta'])) {
    $query_anggota = "SELECT id_anggota, nama FROM Anggota ORDER BY nama ASC";
    $data_anggota = $pdo->query($query_anggota)->fetchAll();
}

if ($table == 'peserta') {
    $query_pelatihan = "SELECT id_pelatihan, judul_pelatihan FROM Pelatihan ORDER BY judul_pelatihan ASC";
    $data_pelatihan = $pdo->query($query_pelatihan)->fetchAll();
}

if ($table == 'pelatihan') {
    $query_narasumber = "SELECT id_narasumber, nama FROM Narasumber ORDER BY nama ASC";
    $data_narasumber = $pdo->query($query_narasumber)->fetchAll();
}

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        switch($table) {
            case 'anggota':
                $query = "UPDATE Anggota SET nama = ?, alamat = ?, no_hp = ? WHERE id_anggota = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['no_hp'], $id]);
                break;
                
            case 'pengurus':
                $query = "UPDATE Pengurus SET jabatan = ?, periode = ?, id_anggota = ? WHERE id_pengurus = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jabatan'], $_POST['periode'], $_POST['id_anggota'], $id]);
                break;
                
            case 'simpanan':
                $query = "UPDATE Simpanan SET jenis = ?, jumlah = ?, tgl_simpan = ?, id_anggota = ? WHERE id_simpanan = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jenis_simpanan'], $_POST['jumlah'], $_POST['tgl_simpan'], $_POST['id_anggota'], $id]);
                break;
                
            case 'pinjaman':
                $query = "UPDATE Pinjaman SET jumlah = ?, tgl_pinjam = ?, tgl_jatuhtempo = ?, status_pinjam = ?, id_anggota = ? WHERE id_pinjaman = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jumlah'], $_POST['tgl_pinjam'], $_POST['tgl_jatuhtempo'], $_POST['status_pinjam'], $_POST['id_anggota'], $id]);
                break;
                
            case 'barang':
                $query = "UPDATE Barang SET nama_barang = ?, harga_jual = ?, stok = ? WHERE id_barang = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama_barang'], $_POST['harga'], $_POST['stok'], $id]);
                break;
                
            case 'pelatihan':
                $query = "UPDATE Pelatihan SET judul_pelatihan = ?, tanggal = ?, tempat = ?, id_narasumber = ? WHERE id_pelatihan = ?";
                $stmt = $pdo->prepare($query);
                $id_narasumber = !empty($_POST['id_narasumber']) ? $_POST['id_narasumber'] : null;
                $stmt->execute([$_POST['judul'], $_POST['tgl_pelatihan'], $_POST['lokasi'], $id_narasumber, $id]);
                break;
                
            case 'peserta':
                $query = "UPDATE Peserta_pelatihan SET status_kehadiran = ?, id_pelatihan = ?, id_anggota = ? WHERE id_peserta = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['status_kehadiran'], $_POST['id_pelatihan'], $_POST['id_anggota'], $id]);
                break;
        }
        
        $success_message = "Data berhasil diupdate!";
        header("refresh:2;url=admin.php?table=" . $table);
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo getTableTitle($table); ?> - KoperasiKu</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="assets/img/logo.svg" alt="KoperasiKu" class="logo-img">
                <span class="logo-text">Koperasi<span class="logo-highlight">Ku</span></span>
            </div>
            <div class="header-right">
                <a href="admin.php?table=<?php echo $table; ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </header>

        <main class="main-content">
            <h1 class="page-title">Edit Data <?php echo getTableTitle($table); ?></h1>

            <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="" class="data-form">
                    <?php include 'forms/form_edit_' . $table . '.php'; ?>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                        <a href="admin.php?table=<?php echo $table; ?>" class="btn-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
