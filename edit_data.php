<?php
require_once 'config/database.php';

$table = $_GET['table'] ?? 'anggota';
$id = $_GET['id'] ?? 0;

$allowed_tables = ['anggota', 'pengurus', 'simpanan', 'pinjaman', 'barang', 'pelatihan', 'peserta', 'penjualan'];

if (!in_array($table, $allowed_tables) || !$id) {
    header("Location: admin.php");
    exit();
}

// Get primary key column name for each table
function getPrimaryKey($table)
{
    $keys = [
        'anggota' => 'id_anggota',
        'pengurus' => 'id_pengurus',
        'simpanan' => 'id_simpanan',
        'pinjaman' => 'id_pinjaman',
        'barang' => 'id_barang',
        'pelatihan' => 'id_pelatihan',
        'peserta' => 'id_peserta',
        'penjualan' => 'id_transaksi'
    ];
    return $keys[$table] ?? 'id';
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
        'penjualan' => 'Penjualan'
    ];
    return $titles[$table] ?? 'Data';
}

$primary_key = getPrimaryKey($table);

// Fetch existing data
try {
    $query = "SELECT * FROM " . ucfirst($table == 'peserta' ? 'Peserta_pelatihan' : ($table == 'penjualan' ? 'Transaksi_penjualan' : $table)) . " WHERE $primary_key = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {
        header("Location: admin.php?table=" . $table);
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch detail items for penjualan
$detail_items = [];
if ($table == 'penjualan') {
    $query_detail = "SELECT * FROM Detail_penjualan WHERE id_transaksi = ?";
    $stmt_detail = $pdo->prepare($query_detail);
    $stmt_detail->execute([$id]);
    $detail_items = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);
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
        switch ($table) {
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
                $query = "UPDATE Barang SET nama_barang = ?, kategori = ?, harga_beli = ?, harga_jual = ?, stok = ? WHERE id_barang = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['nama_barang'], $_POST['kategori'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['stok'], $id]);
                break;

            case 'pelatihan':
                $query = "UPDATE Pelatihan SET judul_pelatihan = ?, tanggal = ?, tempat = ?, id_narasumber = ? WHERE id_pelatihan = ?";
                $stmt = $pdo->prepare($query);
                $id_narasumber = !empty($_POST['id_narasumber']) ? $_POST['id_narasumber'] : null;
                $stmt->execute([$_POST['judul'], $_POST['tgl_pelatihan'], $_POST['lokasi'], $id_narasumber, $id]);
                break;

            case 'peserta':
                $query = "UPDATE Peserta_pelatihan SET status_kehadiran = ?, id_pelatihan = ?, id_anggota = ?, no_sertifikat = ? WHERE id_peserta = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$_POST['status_kehadiran'], $_POST['id_pelatihan'], $_POST['id_anggota'], $_POST['no_sertifikat'], $id]);
                break;

            case 'penjualan':
                // Begin transaction
                $pdo->beginTransaction();

                try {
                    // Restore stock from old items first
                    $query_old_items = "SELECT id_barang, jumlah FROM Detail_penjualan WHERE id_transaksi = ?";
                    $stmt_old = $pdo->prepare($query_old_items);
                    $stmt_old->execute([$id]);
                    $old_items = $stmt_old->fetchAll(PDO::FETCH_ASSOC);

                    $query_restore_stok = "UPDATE Barang SET stok = stok + ? WHERE id_barang = ?";
                    $stmt_restore = $pdo->prepare($query_restore_stok);
                    foreach ($old_items as $old_item) {
                        $stmt_restore->execute([$old_item['jumlah'], $old_item['id_barang']]);
                    }

                    // Delete old details
                    $query_delete = "DELETE FROM Detail_penjualan WHERE id_transaksi = ?";
                    $stmt_delete = $pdo->prepare($query_delete);
                    $stmt_delete->execute([$id]);

                    // Update Transaksi_penjualan
                    $id_pelanggan = ($_POST['jenis_pelanggan'] == 'Anggota' && !empty($_POST['id_pelanggan'])) ? $_POST['id_pelanggan'] : null;

                    $query_transaksi = "UPDATE Transaksi_penjualan SET tgl_transaksi = ?, total_harga = ?, metode_pembayaran = ?, jenis_pelanggan = ?, id_pelanggan = ? WHERE id_transaksi = ?";
                    $stmt_transaksi = $pdo->prepare($query_transaksi);
                    $stmt_transaksi->execute([
                        $_POST['tgl_transaksi'],
                        $_POST['total_harga'],
                        $_POST['metode_pembayaran'],
                        $_POST['jenis_pelanggan'],
                        $id_pelanggan,
                        $id
                    ]);

                    // Insert new details and update stock
                    $query_detail = "INSERT INTO Detail_penjualan (jumlah, harga_satuan, subtotal, id_transaksi, id_barang) VALUES (?, ?, ?, ?, ?)";
                    $stmt_detail = $pdo->prepare($query_detail);

                    $query_update_stok = "UPDATE Barang SET stok = stok - ? WHERE id_barang = ?";
                    $stmt_update_stok = $pdo->prepare($query_update_stok);

                    foreach ($_POST['items'] as $item) {
                        if (!empty($item['id_barang']) && !empty($item['jumlah'])) {
                            $stmt_detail->execute([
                                $item['jumlah'],
                                $item['harga_satuan'],
                                $item['subtotal'],
                                $id,
                                $item['id_barang']
                            ]);

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

        $success_message = "Data berhasil diupdate!";

        // Redirect logic untuk narasumber
        if ($table == 'narasumber') {
            $redirect_table = $_GET['ref'] ?? 'pelatihan';
            $redirect_id = $_GET['ref_id'] ?? '';
            header("refresh:2;url=edit_data.php?table=" . $redirect_table . "&id=" . $redirect_id);
        } else {
            header("refresh:2;url=admin.php?table=" . $table);
        }
    } catch (PDOException $e) {
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
                <a href="<?php
                if ($table == 'narasumber') {
                    $redirect_table = $_GET['ref'] ?? 'pelatihan';
                    $redirect_id = $_GET['ref_id'] ?? '';
                    echo 'edit_data.php?table=' . $redirect_table . '&id=' . $redirect_id;
                } else {
                    echo 'admin.php?table=' . $table;
                }
                ?>" class="btn-back">
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
                    <?php include 'forms/form_' . $table . '.php'; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                        <a href="<?php
                        if ($table == 'narasumber') {
                            $redirect_table = $_GET['ref'] ?? 'pelatihan';
                            $redirect_id = $_GET['ref_id'] ?? '';
                            echo 'edit_data.php?table=' . $redirect_table . '&id=' . $redirect_id;
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
