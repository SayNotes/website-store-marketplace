-- ========================================================
-- 1. MEMBUAT DATABASE & MENGGUNAKANNYA
-- ========================================================
CREATE DATABASE IF NOT EXISTS toko_online_minimal;
USE toko_online_minimal;

-- ========================================================
-- 2. MEMBUAT STRUKTUR TABEL (DENGAN CONSTRAINT RELATION)
-- ========================================================

-- Tabel Pelanggan (User/Pembeli)
CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NULL,
    telepon VARCHAR(15) NULL,
    alamat TEXT NULL
) ENGINE=InnoDB;

-- Tabel Barang (Produk yang Dijual)
CREATE TABLE IF NOT EXISTS barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(150) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    foto VARCHAR(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB;

-- Tabel Transaksi (Nota Induk Transaksi)
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NULL,
    tanggal_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_bayar INT NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    status_transaksi VARCHAR(50) DEFAULT 'Selesai',
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabel Detail Transaksi (Relasi Penghubung Banyak Barang dalam 1 Transaksi)
CREATE TABLE IF NOT EXISTS detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NULL,
    id_barang INT NULL,
    jumlah INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE SET NULL
) ENGINE=InnoDB;


-- ========================================================
-- 3. MENGISI DATA AWAL (DUMMY DATA INJECTION)
-- ========================================================

-- Mengisi Data Pelanggan
INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, email, telepon, alamat) VALUES
(1, 'GUEST_8821', NULL, NULL, NULL),
(2, 'Ahmad Fauzi', 'ahmad@cyber.io', '081234567890', 'Sektor Sembilan, Jakarta'),
(3, 'GUEST_4319', NULL, NULL, NULL);

-- Mengisi Data Barang (Gunakan nama file foto yang sesuai di folder 'uploads')
INSERT INTO barang (id_barang, nama_barang, harga, stok, foto) VALUES
(1, 'Cybernetic Core V.3', 2500000, 15, 'core3.jpg'),
(2, 'Neural Interface Link', 750000, 42, 'interface.jpg'),
(3, 'Quantum Battery Pack', 1200000, 8, 'battery.jpg'),
(4, 'Holotech Display Projector', 3100000, 5, 'projector.jpg'),
(5, 'Nanotech Repair Kit', 450000, 120, 'repairkit.jpg');

-- Mengisi Data Transaksi Induk
INSERT INTO transaksi (id_transaksi, id_pelanggan, total_bayar, metode_pembayaran, status_transaksi) VALUES
(1001, 1, 3250000, 'Dana', 'Selesai'),
(1002, 2, 1200000, 'Gopay', 'Selesai'),
(1003, 3, 900000, 'Ovo', 'Selesai');

-- Mengisi Data Rincian/Detail Transaksi
-- Transaksi 1001: Membeli 1 Cybernetic Core (2.5jt) dan 1 Neural Interface (750rb)
INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) VALUES
(1001, 1, 1, 2500000),
(1001, 2, 1, 750000);

-- Transaksi 1002: Membeli 1 Quantum Battery Pack (1.2jt)
INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) VALUES
(1002, 3, 1, 1200000);

-- Transaksi 1003: Membeli 2 Nanotech Repair Kit (2 x 450rb = 900rb)
INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) VALUES
(1003, 5, 2, 900000);