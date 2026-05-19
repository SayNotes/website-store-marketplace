<?php
session_start();

// 1. VALIDASI AKSES: Jika tidak ada session, kunci dan lempar langsung ke login.php
if (!isset($_SESSION['penjual_id']) || empty($_SESSION['penjual_id'])) { 
    header("Location: login.php");
    exit; 
}

include '../config/database.php';

// 2. AMBIL DATA SESSION
$id_penjual = intval($_SESSION['penjual_id']);
$nama_toko = $_SESSION['penjual_nama'];

// 3. QUERY AMBIL PRODUK (Hanya milik penjual yang sedang login)
$query_produk = "SELECT p.*, k.nama_kategori 
                 FROM produk p 
                 LEFT JOIN kategori k ON p.id_kategori = k.id_kategori 
                 WHERE p.id_penjual = $id_penjual 
                 ORDER BY p.id_produk DESC";
$produk = mysqli_query($conn, $query_produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Toko - <?php echo htmlspecialchars($nama_toko); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Penyesuaian tema gelap Glassmorphic agar serasi dengan login.php */
        body {
            background: #0d0d0f;
            color: #ffffff;
            font-family: system-ui, -apple-system, sans-serif;
            padding: 0;
            margin: 0;
        }
        
        .dashboard-wrapper {
            max-width: 1140px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .glass-card {
            background: rgba(19, 19, 22, 0.75); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(19, 19, 22, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-tambah {
            background: var(--purple-primary, #a855f7);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-tambah:hover {
            background: #9333ea;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 24px;
        }

        th {
            padding: 16px;
            color: var(--text-muted, #9ca3af);
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px;
            background: rgba(255, 255, 255, 0.02);
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #e5e7eb;
        }

        tr td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 12px 0 0 12px;
        }

        tr td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 0 12px 12px 0;
        }

        .action-links a {
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-right: 16px;
            transition: color 0.2s;
        }
        .link-edit { color: #3b82f6; }
        .link-edit:hover { color: #60a5fa; }
        .link-hapus { color: #ef4444; }
        .link-hapus:hover { color: #f87171; }
    </style>
</head>
<body>

    <header>
        <div class="logo" style="font-size: 22px; font-weight:700;">
            <span style="color:#fff;">Seller</span><span style="color:#a855f7;">Panel*</span>
        </div>
        <div style="display: flex; align-items: center; gap: 24px;">
            <span style="font-size: 14px; color: #9ca3af;">Mitra: <strong style="color: #fff;"><?php echo htmlspecialchars($nama_toko); ?></strong></span>
            <a href="logout.php" style="color: #ef4444; text-decoration: none; font-size: 14px; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2); padding: 8px 16px; border-radius: 8px; background: rgba(239, 68, 68, 0.05);">Keluar</a>
        </div>
    </header>

    <div class="dashboard-wrapper">
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div>
                    <h2 style="margin: 0 0 6px 0; font-size: 24px; font-weight: 700;">Katalog Produk Aset Digital</h2>
                    <p style="margin: 0; color: #9ca3af; font-size: 14px;">Kelola komoditas digital yang Anda pasarkan di Inspired Market.</p>
                </div>
                <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 40%;">Nama Produk</th>
                        <th style="width: 20%;">Kategori</th>
                        <th style="width: 15%;">Harga</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($produk) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($produk)): ?>
                        <tr>
                            <td style="color: #a855f7; font-weight: 600;">#<?php echo $row['id_produk']; ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <?php 
                                        // Validasi jika gambar ada di database dan fisik filenya tersedia
                                        $gambar_path = "../assets/img/" . $row['foto'];
                                        $foto_produk = (!empty($row['foto']) && file_exists($gambar_path)) ? $gambar_path : "../assets/img/default-product.png";
                                    ?>
                                    <img src="<?php echo $foto_produk; ?>" alt="Product Image" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02);">
                                    <div>
                                        <div style="font-weight: 600; color: #ffffff;"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                                        <span style="font-size: 12px; color: #6b7280;">Stok: <?php echo $row['stok']; ?> tersedia</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: rgba(168, 85, 247, 0.1); color: #c084fc; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    <?php echo htmlspecialchars($row['nama_kategori'] ?? 'Umum'); ?>
                                </span>
                            </td>
                            <td style="font-weight: 500;">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td class="action-links">
                                <a href="produk.php?id=<?php echo $row['id_produk']; ?>" class="link-edit">Edit</a>
                                <a href="hapus_produk.php?id=<?php echo $row['id_produk']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini dari katalog?')" class="link-hapus">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #6b7280; padding: 40px 0;">
                                📦 Belum ada aset digital yang Anda unggah. Silakan tambah produk baru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>