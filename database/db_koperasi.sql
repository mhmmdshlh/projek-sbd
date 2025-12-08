CREATE DATABASE db_koperasi;
USE db_koperasi;

CREATE TABLE Anggota (
    id_anggota INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(20)
);

CREATE TABLE Narasumber (
    id_narasumber INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    asal VARCHAR(100),
    no_hp VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Barang (
    id_barang INT PRIMARY KEY AUTO_INCREMENT,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50),
    harga_beli DECIMAL(15, 2),
    harga_jual DECIMAL(15, 2),
    stok INT
);

CREATE TABLE Pengurus (
    id_pengurus INT PRIMARY KEY AUTO_INCREMENT,
    jabatan VARCHAR(50),
    periode VARCHAR(50),
    id_anggota INT,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota)
);

CREATE TABLE Simpanan (
    id_simpanan INT PRIMARY KEY AUTO_INCREMENT,
    jenis VARCHAR(50),
    jumlah DECIMAL(15, 2),
    tgl_simpan DATE,
    id_anggota INT,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota)
);

CREATE TABLE Penarikan_simpanan (
    id_penarikan INT PRIMARY KEY AUTO_INCREMENT,
    jumlah_penarikan DECIMAL(15, 2),
    tgl_penarikan DATE,
    id_simpanan INT,
    FOREIGN KEY (id_simpanan) REFERENCES Simpanan(id_simpanan)
);

CREATE TABLE Pinjaman (
    id_pinjaman INT PRIMARY KEY AUTO_INCREMENT,
    jumlah DECIMAL(15, 2),
    tgl_pinjam DATE,
    tgl_jatuhtempo DATE,
    status_pinjam VARCHAR(20),
    id_anggota INT,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota)
);

CREATE TABLE Pembayaran_pinjaman (
    id_pembayaran INT PRIMARY KEY AUTO_INCREMENT,
    jumlah_bayar DECIMAL(15, 2),
    tgl_bayar DATE,
    angsuran_ke INT,
    sisa_pinjaman DECIMAL(15, 2),
    id_pinjaman INT,
    FOREIGN KEY (id_pinjaman) REFERENCES Pinjaman(id_pinjaman)
);

CREATE TABLE Pelanggan (
    id_pelanggan INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    tipe_pelanggan VARCHAR(50),
    no_hp VARCHAR(20),
    alamat TEXT,
    id_anggota INT,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota)
);

CREATE TABLE Pelatihan (
    id_pelatihan INT PRIMARY KEY AUTO_INCREMENT,
    judul_pelatihan VARCHAR(150),
    tanggal DATE,
    tempat VARCHAR(100),
    biaya_pendaftaran DECIMAL(15, 2),
    id_narasumber INT,
    FOREIGN KEY (id_narasumber) REFERENCES Narasumber(id_narasumber)
);

CREATE TABLE Peserta_pelatihan (
    id_peserta INT PRIMARY KEY AUTO_INCREMENT,
    status_kehadiran VARCHAR(20),
    sertifikat VARCHAR(100), -- Bisa berupa nomor sertifikat atau status (Sudah/Belum)
    id_pelatihan INT,
    id_anggota INT,
    FOREIGN KEY (id_pelatihan) REFERENCES Pelatihan(id_pelatihan),
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota)
);

CREATE TABLE Transaksi_penjualan (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    tgl_transaksi DATE,
    total_harga DECIMAL(15, 2),
    metode_pembayaran VARCHAR(50),
    id_pelanggan INT,
    FOREIGN KEY (id_pelanggan) REFERENCES Pelanggan(id_pelanggan)
);

CREATE TABLE Detail_penjualan (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    jumlah INT,
    harga_satuan DECIMAL(15, 2),
    subtotal DECIMAL(15, 2),
    id_transaksi INT,
    id_barang INT,
    FOREIGN KEY (id_transaksi) REFERENCES Transaksi_penjualan(id_transaksi),
    FOREIGN KEY (id_barang) REFERENCES Barang(id_barang)
);
