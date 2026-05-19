<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/database.php';

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

$penjual = mysqli_query($conn, "SELECT * FROM penjual ORDER BY id_penjual DESC");
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
        /* ==========================================================================
           RE-DESIGN PANEL ADMIN: THEMA DEEP PURPLE GLASSMORPHISM
           ========================================================================== */
        body {
            background-color: #0b0a0f !important;
            color: #ffffff !important;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Modifikasi Header Admin Minimalis */
        header {
            background: rgba(15, 12, 26, 0.7) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 18px 40px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header .logo {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        header .logo span {
            color: #a855f7;
            text-shadow: 0 0 12px rgba(168, 85, 247, 0.5);
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444 !important;
            padding: 8px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #ffffff !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
            transform: translateY(-1px);
        }

        /* Container Panel Utama */
        .admin-main-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .admin-title-zone {
            margin-bottom: 24px;
        }

        .admin-title-zone h2 {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 6px 0;
        }

        .admin-title-zone p {
            color: #8b869c;
            font-size: 14px;
            margin: 0;
        }

        /* Desain Kapsul Tabel Transparan Ungu Premium */
        .table-responsive-wrapper {
            background: rgba(22, 19, 34, 0.5) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 20px;
            padding: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            overflow-x: auto; /* Mencegah tabel pecah di device kecil */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent !important;
            border: none !important;
            min-width: 800px;
        }

        th {
            background: rgba(168, 85, 247, 0.08) !important;
            color: #c084fc !important;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        td {
            padding: 16px 20px;
            color: #e2e8f0;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            vertical-align: middle;
        }

        /* Efek Hover Baris Neon Glow */
        tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Tag ID Modis */
        .id-tag {
            font-family: monospace;
            color: #a855f7;
            font-weight: bold;
            font-size: 14px;
        }

        /* Style Kapsul Badge Status */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.1) !important;
            color: #4ade80 !important;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .badge-suspended {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #f87171 !important;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Tombol Aksi Kontrol Pengawasan */
        .action-link {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-right: 8px;
        }

        .action-suspend {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .action-suspend:hover {
            background: #d97706;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(217, 119, 6, 0.3);
        }

        .action-activate {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .action-activate:hover {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.3);
        }

        .action-delete {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .action-delete:hover {
            background: #dc2626;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(220, 38, 38, 0.3);
        }
    </style>
</head>
<body>

    <div class="grid-overlay" style="opacity: 0.08;"></div>
    <div class="purple-bokeh" style="top: -20%; left: 70%; width: 500px; height: 500px;"></div>

    <header>
        <div class="logo">Admin<span>Panel</span></div>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </header>

    <div class="admin-main-wrapper">
        <div class="admin-title-zone">
            <h2>Manajemen & Pengawasan Penjual</h2>
            <p>Kelola hak akses toko, tinjau status aktif, atau tangguhkan kemitraan penjual yang bermasalah.</p>
        </div>

        <div class="table-responsive-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ID Penjual</th>
                        <th style="width: 30%;">Nama Toko / Penjual</th>
                        <th style="width: 25%;">Email Terdaftar</th>
                        <th style="width: 15%;">Status Akun</th>
                        <th style="width: 20%; text-align: center;">Aksi Pengawasan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($penjual) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($penjual)): ?>
                        <tr>
                            <td><span class="id-tag">#<?php echo $row['id_penjual']; ?></span></td>
                            <td><strong style="color: #ffffff; font-weight: 600;"><?php echo htmlspecialchars($row['nama_toko']); ?></strong></td>
                            <td style="color: #94a3b8;"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $row['status'] == 'aktif' ? 'badge-active' : 'badge-suspended'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if($row['status'] == 'aktif'): ?>
                                    <a href="dashboard.php?action=suspend&id=<?php echo $row['id_penjual']; ?>" class="action-link action-suspend">Tangguhkan</a>
                                <?php else: ?>
                                    <a href="dashboard.php?action=activate&id=<?php echo $row['id_penjual']; ?>" class="action-link action-activate">Aktifkan</a>
                                <?php endif; ?>
                                
                                <a href="dashboard.php?delete=<?php echo $row['id_penjual']; ?>" 
                                   onclick="return confirm('Hapus penjual ini? Semua produk yang berafiliasi dengan mereka juga akan terhapus secara permanen.')" 
                                   class="action-link action-delete">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 40px 0;">Belum ada data penjual yang terdaftar di database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Animasi halus kemunculan baris tabel admin saat dimuat
        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".admin-title-zone", { duration: 0.6, y: -20, opacity: 0, ease: "power2.out" });
                gsap.from("tbody tr", { duration: 0.4, opacity: 0, y: 10, stagger: 0.04, ease: "power2.out", delay: 0.1 });
            }
        });
    </script>
</body>
</html>