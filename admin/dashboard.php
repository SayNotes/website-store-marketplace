<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/database.php';

// --- PROSES TAMBAH BANNER CAROUSEL ---
if (isset($_POST['add_carousel'])) {
    if ($_FILES['foto']['name'] != "") {
        $filename = $_FILES['foto']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        // Enkripsi nama file unik dengan timestamp agar tidak bentrok
        $foto_baru = "banner_" . time() . "." . $ext;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/carousel/" . $foto_baru)) {
            mysqli_query($conn, "INSERT INTO carousel (foto) VALUES ('$foto_baru')");
        }
    }
    header("Location: dashboard.php");
    exit;
}

// --- PROSES HAPUS BANNER CAROUSEL ---
if (isset($_GET['delete_carousel'])) {
    $id_c = intval($_GET['delete_carousel']);
    $res_c = mysqli_query($conn, "SELECT foto FROM carousel WHERE id_carousel=$id_c");
    if ($row_c = mysqli_fetch_assoc($res_c)) {
        // Hapus file fisik dari server jika ada
        if(file_exists("../assets/img/carousel/" . $row_c['foto'])) {
            @unlink("../assets/img/carousel/" . $row_c['foto']);
        }
    }
    mysqli_query($conn, "DELETE FROM carousel WHERE id_carousel=$id_c");
    header("Location: dashboard.php");
    exit;
}

// Proses Update Status Penjual (Aksi Penangguhan)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'suspend') ? 'ditangguhkan' : 'aktif';
    mysqli_query($conn, "UPDATE penjual SET status='$status' WHERE id_penjual=$id");
    header("Location: dashboard.php");
    exit;
}

// Proses Hapus Penjual
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM penjual WHERE id_penjual=$id");
    header("Location: dashboard.php");
    exit;
}

// AMBIL DATA PENJUAL
$penjual = mysqli_query($conn, "SELECT * FROM penjual ORDER BY id_penjual DESC");

// AMBIL SEMUA DATA TRANSAKSI UTK ADMIN (BARU)
$query_all_transaksi = "SELECT t.*, p.nama_produk, p.foto, j.nama_toko 
                        FROM transaksi t
                        JOIN produk p ON t.id_produk = p.id_produk
                        JOIN penjual j ON t.id_penjual = j.id_penjual
                        ORDER BY t.tanggal DESC";
$all_transaksi = mysqli_query($conn, $query_all_transaksi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Panel Pengawasan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            background: #0d0d0f;
            color: #ffffff;
            font-family: system-ui, -apple-system, sans-serif;
        }
        /* Style Status Badge Transaksi */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-selesai { background: rgba(34, 197, 94, 0.1); color: #4ade80; }
        .status-pending { background: rgba(234, 179, 8, 0.1); color: #facc15; }
        .status-dibatalkan { background: rgba(239, 68, 68, 0.1); color: #f87171; }
    </style>
</head>
<body>

    <div class="admin-panel-container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; flex-direction: column; gap: 40px;">
        
        <div class="admin-title-zone" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 26px; font-weight: 700; margin: 0;">Panel Pengawasan <span style="color:#a855f7;">Admin Loh Ya😹</span></h2>
                <p style="color: #64748b; margin: 5px 0 0 0;">Kelola data kemitraan toko, banner promosi, dan pantau transaksi global</p>
            </div>
            <a href="logout.php" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Keluar Log</a>
        </div>

        <div style="background: rgba(19, 19, 22, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); padding: 25px; border-radius: 20px;">
            <h3 style="margin-top: 0; font-size: 18px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">🖼️ Manajemen Banner Carousel</h3>
            
            <form action="" method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; align-items: flex-end; margin-bottom: 25px; background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.04);">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 13px; color: #94a3b8; margin-bottom: 6px; font-weight: 600;">Pilih File Gambar Banner (Rekomendasi ukuran 1200x380)</label>
                    <input type="file" name="foto" required style="width: 100%; padding: 10px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; box-sizing: border-box;">
                </div>
                <button type="submit" name="add_carousel" style="background: #a855f7; color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">⚡ Unggah Banner</button>
            </form>

            <h4 style="font-size: 14px; margin-bottom: 12px; color: #94a3b8;">Daftar Banner Aktif Saat Ini:</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px;">
                <?php
                $res_list = mysqli_query($conn, "SELECT * FROM carousel ORDER BY id_carousel DESC");
                if ($res_list && mysqli_num_rows($res_list) > 0):
                    while ($c_row = mysqli_fetch_assoc($res_list)):
                ?>
                    <div style="position: relative; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); background: #0b0b0c;">
                        <img src="../assets/img/carousel/<?php echo $c_row['foto']; ?>" style="width: 100%; height: 110px; object-fit: cover; display: block;">
                        <div style="padding: 8px; text-align: center; background: rgba(0,0,0,0.5);">
                            <a href="dashboard.php?delete_carousel=<?php echo $c_row['id_carousel']; ?>" onclick="return confirm('Hapus banner ini dari daftar utama?')" style="color: #f87171; font-size: 12px; text-decoration: none; font-weight: 600;">🗑️ Hapus Banner</a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 25px; border: 1px dashed rgba(255,255,255,0.1); border-radius: 12px; font-size: 13px;">
                        💡 Belum ada data banner custom. Sistem otomatis beralih memuat 3 Image Default bawaan aplikasi.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="background: rgba(19, 19, 22, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 25px;">
            <h3 style="margin-top: 0; font-size: 18px; margin-bottom: 15px;">🏪 Manajemen Kemitraan Penjual</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08); color: #64748b; font-size: 13px;">
                            <th style="padding: 14px;">Nama Toko</th>
                            <th style="padding: 14px;">Email Akun</th>
                            <th style="padding: 14px;">Status Berjalan</th>
                            <th style="padding: 14px; text-align: right;">Aksi Kontrol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($penjual) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($penjual)): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); font-size: 14px; transition: 0.2s;">
                                <td style="padding: 16px; font-weight: 600;"><?php echo htmlspecialchars($row['nama_toko']); ?></td>
                                <td style="padding: 16px; color: #cbd5e1;"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td style="padding: 16px;">
                                    <?php if ($row['status'] == 'aktif'): ?>
                                        <span style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">Aktif</span>
                                    <?php else: ?>
                                        <span style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">Ditangguhkan</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 16px; text-align: right;" class="action-links">
                                    <?php if ($row['status'] == 'aktif'): ?>
                                        <a href="dashboard.php?action=suspend&id=<?php echo $row['id_penjual']; ?>" onclick="return confirm('Tangguhkan hak akses penjual ini?')" style="color: #fbbf24; text-decoration: none; margin-right: 15px; font-size: 13px; font-weight: 600;">Suspend</a>
                                    <?php else: ?>
                                        <a href="dashboard.php?action=activate&id=<?php echo $row['id_penjual']; ?>" style="color: #4ade80; text-decoration: none; margin-right: 15px; font-size: 13px; font-weight: 600;">Aktifkan</a>
                                    <?php endif; ?>
                                    
                                    <a href="dashboard.php?delete=<?php echo $row['id_penjual']; ?>" 
                                       onclick="return confirm('Hapus penjual ini? Semua produk yang berafiliasi dengan mereka juga akan terhapus secara permanen.')" 
                                       style="color: #f87171; text-decoration: none; font-size: 13px; font-weight: 600;">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 40px 0;">Belum ada data penjual yang terdaftar di database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="background: rgba(19, 19, 22, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 25px;">
            <h3 style="margin-top: 0; font-size: 18px; margin-bottom: 5px;">💸 Pemantauan Transaksi Global</h3>
            <p style="color: #64748b; margin: 0 0 20px 0; font-size: 14px;">Catatan riwayat transaksi menyeluruh dari seluruh ekosistem merchant/toko.</p>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08); color: #64748b; font-size: 13px;">
                            <th style="padding: 14px;">Waktu Transaksi</th>
                            <th style="padding: 14px;">Asal Toko</th>
                            <th style="padding: 14px;">Aset / Produk</th>
                            <th style="padding: 14px;">Nama Pembeli</th>
                            <th style="padding: 14px;">Nilai Transaksi</th>
                            <th style="padding: 14px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($all_transaksi) > 0): ?>
                            <?php while ($trx = mysqli_fetch_assoc($all_transaksi)): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); font-size: 14px;">
                                <td style="padding: 16px; color: #94a3b8; font-size: 13px;">
                                    <?php echo date('d M Y, H:i', strtotime($trx['tanggal'])); ?>
                                </td>
                                <td style="padding: 16px; font-weight: 500; color: #a855f7;">
                                    <?php echo htmlspecialchars($trx['nama_toko']); ?>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php 
                                            $img_path = "../assets/img/" . $trx['foto'];
                                            $src_img = (!empty($trx['foto']) && file_exists($img_path)) ? $img_path : "../assets/img/default-product.png";
                                        ?>
                                        <img src="<?php echo $src_img; ?>" style="width: 32px; height: 32px; border-radius: 6px; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars($trx['nama_produk']); ?></div>
                                            <span style="font-size: 11px; color: #64748b;">Jumlah: <?php echo $trx['jumlah']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px; color: #cbd5e1;">
                                    <?php echo htmlspecialchars($trx['nama_pembeli']); ?>
                                </td>
                                <td style="padding: 16px; font-weight: 600; color: #22c55e;">
                                    Rp <?php echo number_format($trx['total_harga'], 0, ',', '.'); ?>
                                </td>
                                <td style="padding: 16px;">
                                    <span class="badge-status status-<?php echo strtolower($trx['status']); ?>">
                                        <?php echo $trx['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #64748b; padding: 40px 0;">Belum ada aktivitas transaksi yang terekam pada sistem.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".admin-title-zone", { duration: 0.6, y: -20, opacity: 0, ease: "power2.out" });
            }
        });
    </script>
</body>
</html>