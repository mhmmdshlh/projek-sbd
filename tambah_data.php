<?php
require_once 'config/database.php';

$table = $_GET['table'] ?? 'anggota';
$allowed_tables = ['anggota', 'pengurus', 'simpanan', 'pinjaman', 'barang', 'pelatihan', 'peserta', 'narasumber', 'penjualan'];

if (!in_array($table, $allowed_tables)) {
    header("Location: admin.php");
    exit();
}

// Fetch related data for dropdowns
$data_anggota = [];
$data_pelatihan = [];
$data_narasumber = [];
$data_barang = [];

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
        switch ($table) {
            case 'anggota':
                $query = "INSERT INTO Anggota (nama, alamat, no_hp) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['no_hp']]);
                break;

            case 'pengurus':
                $query = "INSERT INTO Pengurus (jabatan, periode, id_anggota) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jabatan'], $_POST['periode'], $_POST['id_anggota']]);
                break;

            case 'simpanan':
                $query = "INSERT INTO Simpanan (jenis, jumlah, tgl_simpan, id_anggota) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jenis_simpanan'], $_POST['jumlah'], $_POST['tgl_simpan'], $_POST['id_anggota']]);
                break;

            case 'pinjaman':
                $query = "INSERT INTO Pinjaman (jumlah, tgl_pinjam, tgl_jatuhtempo, status_pinjam, id_anggota) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['jumlah'], $_POST['tgl_pinjam'], $_POST['tgl_jatuhtempo'], $_POST['status_pinjam'], $_POST['id_anggota']]);
                break;

            case 'barang':
                $query = "INSERT INTO Barang (nama_barang, kategori, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama_barang'], $_POST['kategori'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['stok']]);
                break;

            case 'pelatihan':
                $query = "INSERT INTO Pelatihan (judul_pelatihan, tanggal, tempat, biaya_pendaftaran, id_narasumber) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $id_narasumber = !empty($_POST['id_narasumber']) ? $_POST['id_narasumber'] : null;
                $stmt->execute([$_POST['judul'], $_POST['tgl_pelatihan'], $_POST['lokasi'], $_POST['id_biaya'], $id_narasumber]);
                break;

            case 'peserta':
                $query = "INSERT INTO Peserta_pelatihan (status_kehadiran, id_pelatihan, id_anggota, no_sertifikat) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['status_kehadiran'], $_POST['id_pelatihan'], $_POST['id_anggota'], $_POST['no_sertifikat']]);
                break;

            case 'narasumber':
                $query = "INSERT INTO Narasumber (nama, asal, no_hp, email) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama'], $_POST['asal'], $_POST['no_hp'], $_POST['email']]);
                break;

            case 'penjualan':
                // Begin transaction
                $pdo->beginTransaction();

                try {
                    // Insert into Transaksi_penjualan
                    $id_pelanggan = ($_POST['jenis_pelanggan'] == 'Anggota' && !empty($_POST['id_pelanggan'])) ? $_POST['id_pelanggan'] : null;

                    $query_transaksi = "INSERT INTO Transaksi_penjualan (tgl_transaksi, total_harga, metode_pembayaran, jenis_pelanggan, id_pelanggan) 
                                       VALUES (?, ?, ?, ?, ?)";
                    $stmt_transaksi = $pdo->prepare($query_transaksi);
                    $stmt_transaksi->execute([
                        $_POST['tgl_transaksi'],
                        $_POST['total_harga'],
                        $_POST['metode_pembayaran'],
                        $_POST['jenis_pelanggan'],
                        $id_pelanggan
                    ]);

                    $id_transaksi = $pdo->lastInsertId();

                    // Insert into Detail_penjualan
                    $query_detail = "INSERT INTO Detail_penjualan (jumlah, harga_satuan, subtotal, id_transaksi, id_barang) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $stmt_detail = $pdo->prepare($query_detail);

                    // Update stock
                    $query_update_stok = "UPDATE Barang SET stok = stok - ? WHERE id_barang = ?";
                    $stmt_update_stok = $pdo->prepare($query_update_stok);

                    foreach ($_POST['items'] as $item) {
                        if (!empty($item['id_barang']) && !empty($item['jumlah'])) {
                            // Insert detail
                            $stmt_detail->execute([
                                $item['jumlah'],
                                $item['harga_satuan'],
                                $item['subtotal'],
                                $id_transaksi,
                                $item['id_barang']
                            ]);

                            // Update stock
                            $stmt_update_stok->execute([
                                $item['jumlah'],
                                $item['id_barang']
                            ]);
                        }
                    }

                    $pdo->commit();
                } catch (Exception $e) {
                    $pdo->rollBack();
                    throw $e;
                }
                break;
        }

        $success_message = "Data berhasil ditambahkan!";

        // Redirect logic untuk narasumber
        if ($table == 'narasumber') {
            $redirect_table = $_GET['ref'] ?? 'pelatihan';
            $redirect_id = $_GET['ref_id'] ?? '';
            $is_edit = $_GET['edit'] ?? '';

            if ($is_edit && $redirect_id) {
                header("refresh:2;url=edit_data.php?table=" . $redirect_table . "&id=" . $redirect_id);
            } else {
                header("refresh:2;url=tambah_data.php?table=" . $redirect_table);
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

function getTableTitle($table)
{
    $titles = [
        'anggota' => 'Anggota',
        'pengurus' => 'Pengurus',
        'simpanan' => 'Simpanan',
        'pinjaman' => 'Pinjaman',
        'barang' => 'Barang',
        'pelatihan' => 'Pelatihan',
        'peserta' => 'Peserta Pelatihan',
        'narasumber' => 'Narasumber',
        'penjualan' => 'Penjualan'
    ];
    return $titles[$table] ?? 'Data';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah <?php echo getTableTitle($table); ?> - KoperasiKu</title>
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
                <a href="<?php
                if ($table == 'narasumber') {
                    $redirect_table = $_GET['ref'] ?? 'pelatihan';
                    $redirect_id = $_GET['ref_id'] ?? '';
                    $is_edit = $_GET['edit'] ?? '';
                    echo ($is_edit && $redirect_id)
                        ? 'edit_data.php?table=' . $redirect_table . '&id=' . $redirect_id
                        : 'tambah_data.php?table=' . $redirect_table;
                } else {
                    echo 'admin.php?table=' . $table;
                }
                ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </header>

        <main class="main-content">
            <h1 class="page-title">Tambah Data <?php echo getTableTitle($table); ?></h1>

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
                    <?php include 'forms/form_' . $table . '.php'; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                        <a href="<?php
                        if ($table == 'narasumber') {
                            $redirect_table = $_GET['ref'] ?? 'pelatihan';
                            $redirect_id = $_GET['ref_id'] ?? '';
                            $is_edit = $_GET['edit'] ?? '';
                            echo ($is_edit && $redirect_id)
                                ? 'edit_data.php?table=' . $redirect_table . '&id=' . $redirect_id
                                : 'tambah_data.php?table=' . $redirect_table;
                        } else {
                            echo 'admin.php?table=' . $table;
                        }
                        ?>" class="btn-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
