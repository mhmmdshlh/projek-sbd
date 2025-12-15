<?php
include 'config/database.php';

// Fetch barang data
$stmt = $pdo->query("SELECT * FROM Barang");
$barang = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoperasiKu - Manajemen Barang</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Gaegu&display=swap" />

    <style>
        /* ========================================= */
        /* --- 1. GLOBAL & RESET --- */
        /* ========================================= */
        body {
            margin: 0;
            line-height: normal;
            background-color: #f0f0f0;
            overflow-x: hidden;
            /* Mencegah scroll samping berlebih */
        }

        /* ========================================= */
        /* --- 2. LAYOUT UTAMA --- */
        /* ========================================= */
        .desktop-18 {
            width: 100%;
            min-height: 100vh;
            /* Minimal setinggi layar */
            height: 3072px;
            /* Tinggi original desain Anda */
            position: relative;
            background-color: #fff;
            overflow: hidden;
            text-align: left;
            font-family: 'Poppins', sans-serif;
            margin: 0 auto;
        }

        /* Background Images Placeholder */
        .image-954-icon {
            position: absolute;
            object-fit: cover;
            flex-shrink: 0;
            background-color: #ddd;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -3;
            /* Behind other elements */
            opacity: 0.7;
            /* Semi-transparent */
        }

        .image-953-icon {
            position: absolute;
            object-fit: cover;
            flex-shrink: 0;
            background-color: #ddd;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -2;
            /* Behind other elements */
            opacity: 0.7;
            /* Semi-transparent */
        }

        .image-952-icon {
            position: absolute;
            object-fit: cover;
            flex-shrink: 0;
            background-color: #ddd;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
            /* Behind other elements */
            opacity: 0.7;
            /* Semi-transparent */
        }

        .desktop-18-child {
            position: absolute;
            top: 117px;
            left: 0px;
            background-color: rgba(255, 255, 255, 0.5);
            width: 100%;
            height: 2955px;
            z-index: 1;
        }

        /* Header & Navigasi */
        .rectangle-parent {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 118px;
            font-size: 18px;
            color: #82898d;
            font-family: 'Montserrat', sans-serif;
            z-index: 10;
            /* Agar di atas elemen lain */
        }

        .group-child {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: #d9d9d9;
            width: 100%;
            height: 100%;
        }

        .tentang-kami {
            position: absolute;
            top: 45px;
            right: 250px;
            font-weight: 600;
            cursor: pointer;
        }

        .masuklogin {
            position: absolute;
            top: 45px;
            right: 100px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ========================================= */
        /* --- 3. PERBAIKAN LOGO (KoperasiKu) --- */
        /* ========================================= */
        .objects-parent {
            position: absolute;
            top: 25px;
            left: 50px;
            /* Jarak dari kiri layar */
            z-index: 20;
            display: flex;
            /* Flexbox agar Ikon dan Teks sejajar */
            align-items: center;
            /* Vertikal center */
            gap: 15px;
            /* Jarak antara ikon dan tulisan */
        }

        .objects-icon {
            width: 70px;
            /* Ukuran ikon disesuaikan */
            height: auto;
        }

        .texts {
            font-family: 'Gaegu', cursive;
            font-size: 60px;
            color: #212531;
            line-height: 1;
            margin-top: 10px;
            /* Sedikit penyesuaian vertikal font Gaegu */
        }

        .koperasiku {
            white-space: nowrap;
            /* KUNCI: Mencegah 'Ku' turun ke bawah */
        }

        .ku {
            color: #f9b012;
        }

        /* ========================================= */
        /* --- 4. JUDUL HALAMAN --- */
        /* ========================================= */
        .list-barang-wrapper {
            position: absolute;
            top: 200px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
            z-index: 101;
            font-size: 50px;
            font-weight: 600;
            color: #2d2d2d;
        }

        .list-barang-wrapper span {
            color: #f9b012;
        }

        /* ========================================= */
        /* --- 5. STYLE CARD BARU (FORM & TABEL) --- */
        /* ========================================= */

        .koperasi-card {
            position: absolute;
            top: 350px;
            /* Posisi di bawah judul Sembako */
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1100px;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
            color: #333;
            z-index: 100;
        }

        /* Judul Section dengan Garis Oranye */
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .orange-bar {
            width: 5px;
            height: 30px;
            background-color: #F9B012;
            margin-right: 15px;
            border-radius: 2px;
        }

        .section-title h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #222;
        }

        /* Form Grid System */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .span-2 {
            grid-column: span 2;
        }

        /* Nama barang ambil 2 kolom */

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-group input,
        .input-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
            background-color: #fff;
            width: 100%;
            box-sizing: border-box;
            /* Agar padding tidak merusak width */
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #F9B012;
            box-shadow: 0 0 0 3px rgba(249, 176, 18, 0.1);
        }

        .btn-simpan {
            width: 100%;
            background-color: #F9B012;
            color: white;
            font-weight: 700;
            border: none;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background-color: #e09b0a;
        }

        /* Tabel Styling */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .table-inventory {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .table-inventory thead {
            background-color: #222;
            color: white;
        }

        .table-inventory th {
            padding: 15px;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .table-inventory td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            color: #444;
        }

        .text-green {
            color: #2e7d32;
            font-weight: 700;
        }

        .badge-low {
            background-color: #ffebee;
            color: #d32f2f;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            display: inline-block;
        }

        .btn-edit {
            background: white;
            border: 1px solid #ddd;
            padding: 6px 15px;
            border-radius: 4px;
            cursor: pointer;
            color: #555;
            font-size: 12px;
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        /* Responsif HP */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .span-2 {
                grid-column: span 1;
            }

            .objects-parent {
                left: 20px;
            }

            .texts {
                font-size: 40px;
            }

            .tentang-kami,
            .masuklogin {
                position: relative;
                top: auto;
                right: auto;
                display: inline-block;
                margin: 10px;
            }
        }

        /* ========================================= */
        /* --- 6. FOOTER --- */
        /* ========================================= */
        .lihat-selengkapnya-wrapper {
            position: absolute;
            top: 2800px;
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .footer-text {
            font-size: 40px;
            font-weight: 600;
            color: #2d2d2d;
        }

        .footer-box {
            position: absolute;
            top: 2900px;
            left: 5%;
            width: 90%;
            height: 100px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .map-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #eee;
            margin-right: 20px;
        }

        .footer-locations {
            font-size: 20px;
            font-weight: 600;
            color: #2d2d2d;
        }
    </style>
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

    <div class="desktop-18">
        <img class="image-954-icon" alt="" src="bg1.png">
        <img class="image-953-icon" alt="" src="bg2.png">
        <img class="image-952-icon" alt="" src="bg3.png">

        <div class="desktop-18-child"></div>

        <div class="list-barang-wrapper">List <span>Barang</span></div>

        <div class="koperasi-card">

            <div class="section-title">
                <div class="orange-bar"></div>
                <h3>Daftar Inventaris Barang</h3>
            </div>

            <div class="table-wrapper">
                <table class="table-inventory">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barang as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($item['kategori']); ?></td>
                                <td>Rp <?php echo number_format($item['harga_jual'], 0, ',', '.'); ?></td>
                                <td class="<?php echo $item['stok'] < 20 ? 'badge-low' : 'text-green'; ?>">
                                    <?php if ($item['stok'] < 20): ?>
                                        <span class="badge-low"><?php echo htmlspecialchars($item['stok']); ?>
                                            (Low)</span>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($item['stok']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>Contact | Social Media | Alamat | © 2025 KoperasiKu</p>
    </footer>
</body>

</html>
