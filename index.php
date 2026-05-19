<?php
// Hubungkan ke database mp_purple kamu di Laragon
require_once __DIR__ . '/config/database.php';

// Ambil data produk asli dari database beserta nama tokonya
$query = "SELECT produk.*, penjual.nama_toko 
          FROM produk 
          JOIN penjual ON produk.id_penjual = penjual.id_penjual 
          WHERE penjual.status = 'aktif' AND produk.stok > 0
          ORDER BY produk.id_produk DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspired Market - Marketplace</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* ==========================================================================
           FORCE RESET & KLONING STYLE DARI PENCARIAN.PHP (100% IDENTIK)
           ========================================================================== */
        body {
            background-color: #0b0b0c !important;
            color: #ffffff !important;
        }

        /* Override Grid Utama Halaman Index Menjadi Kloning Pencarian 4 Kolom */
        .products-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important; /* Paksa pas 4 kolom kesamping */
            gap: 20px !important; /* Jarak antar card rapat proporsional */
            padding: 30px 0 !important;
        }

        /* KLONING DESAIN SLEEK CARD DARI PENCARIAN.PHP */
        .sleek-card-index {
            background: rgba(30, 27, 46, 0.4) !important; /* Warna ungu glassmorphic pencarian */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 24px !important;
            padding: 16px !important; /* Ukuran padding ringkas pencarian */
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            position: relative !important;
            opacity: 1 !important; 
            visibility: visible !important;
            transition: all 0.3s ease;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            text-align: left !important;
        }

        /* Hover Effect Glow Ungu persis pencarian.php */
        .sleek-card-index:hover {
            transform: translateY(-6px);
            border-color: #a855f7 !important;
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.25);
        }

        /* Kotak Preview Gambar Kloning Pencarian */
        .sleek-card-index .preview-wrapper {
            width: 100%;
            height: 150px; /* Ukuran tinggi pas, tidak melar */
            border-radius: 16px;
            background-size: cover;
            background-position: center;
            background-color: #1e1e24 !important;
            position: relative;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Badge Harga Mengambang Di Atas Gambar */
        .sleek-card-index .floating-price {
            position: absolute;
            left: 12px;
            bottom: 12px;
            background: #0b0b0c !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        /* Sparkle Badge */
        .sleek-card-index .sparkle-badge {
            position: absolute;
            right: 12px;
            top: 12px;
            background: linear-gradient(135deg, #a855f7, #d946ef);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
        }

        /* Judul Barang Putih Tegas */
        .sleek-card-index .product-title {
            font-size: 15px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            margin: 0 0 6px 0 !important;
            line-height: 1.4;
            display: block;
        }

        /* Nama Toko / Seller */
        .sleek-card-index .seller-tag {
            font-size: 13px !important;
            color: #9ca3af !important;
            margin-bottom: 4px !important;
        }

        .sleek-card-index .seller-tag span {
            color: #c084fc !important;
            font-weight: 600;
        }

        /* Info Stok */
        .sleek-card-index .stock-info-text {
            font-size: 12px !important;
            color: #6b7280 !important;
            margin-bottom: 16px !important;
        }

        /* Baris Tombol Aksi */
        .sleek-card-index .action-row {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin-top: auto !important;
        }

        /* Tombol Cart Kloning Pencarian */
        .sleek-card-index .btn-cart-minimal {
            flex: 1 !important;
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            padding: 11px !important;
            border-radius: 12px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center !important;
        }

        .sleek-card-index .btn-cart-minimal:hover {
            background: #a855f7 !important;
            border-color: #a855f7 !important;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.4);
        }

        /* Counter Kuantitas Kloning Pencarian */
        .quantity-counter {
            display: flex !important;
            align-items: center !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            background: #1e1e24 !important;
            border-radius: 12px !important;
            padding: 4px !important;
        }

        .count-btn {
            background: none !important;
            border: none !important;
            font-size: 14px !important;
            font-weight: bold !important;
            cursor: pointer !important;
            padding: 4px 10px !important;
            color: #ffffff !important;
        }

        .qty-input {
            border: none !important;
            background: none !important;
            text-align: center !important;
            width: 24px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            outline: none !important;
        }

        /* Responsive Breakpoints Grid */
        @media (max-width: 1100px) {
            .products-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }
        @media (max-width: 800px) {
            .products-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 500px) {
            .products-grid { grid-template-columns: repeat(1, 1fr) !important; }
        }
    </style>
</head>

<body>

    <div class="grid-overlay" style="opacity: 0.15;"></div>
    <div class="purple-bokeh"></div>

    <header>
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Market</span><span class="purple">Inspired</span>
            </div>

            <form action="pencarian.php" method="GET" class="search-box">
                <input type="text" name="query" placeholder="Cari UI kit, template, kreator..." required>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                    </svg>
                </button>
            </form>

            <div class="nav-actions">
                <a href="keranjang.php" class="nav-icon-link" title="Keranjang Belanja">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </a>

                <div class="dropdown">
                    <div class="profile-group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span>Akun ▾</span>
                    </div>
                    <div class="dropdown-content">
                        <a href="penjual/login.php">Dashboard Toko</a>
                        <a href="admin/login.php">Panel Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="hero-section">
            <h1 class="hero-title">
                <span class="white">Design inspiration</span><br>
                <span class="purple-text">websites*</span>
            </h1>
            <p class="hero-subtitle">Platform marketplace asset digital premium untuk menunjang kebutuhan kreatif
                pembuatan website Anda.</p>
        </div>
    </div>

    <section class="container resources-section">
        <h2 class="section-title">Katalog Produk Kreatif</h2>

        <div class="products-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    
                    <div class="sleek-card-index">
                        <div>
                            <div class="preview-wrapper" style="background-image: url('assets/img/<?php echo !empty($row['foto']) ? $row['foto'] : 'default.jpg'; ?>')">
                                <div class="floating-price">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </div>
                                <div class="sparkle-badge">✦</div>
                            </div>
                            
                            <div class="card-info">
                                <div class="seller-tag">
                                    by <span><?php echo htmlspecialchars($row['nama_toko']); ?></span>
                                </div>
                                <h3 class="product-title"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                                <div class="stock-info-text">Tersedia: <?php echo $row['stok']; ?> pcs</div>
                            </div>
                        </div>

                        <div class="action-row">
                            <div class="quantity-counter">
                                <button type="button" class="count-btn" onclick="ubahAngka(this, -1)">−</button>
                                <input type="number" class="qty-input" id="qty-<?php echo $row['id_produk']; ?>" value="1" min="1" max="<?php echo $row['stok']; ?>" readonly>
                                <button type="button" class="count-btn" onclick="ubahAngka(this, 1, <?php echo $row['stok']; ?>)">+</button>
                            </div>
                            
                            <button class="btn-cart-minimal" onclick="prosesKeranjang(<?php echo $row['id_produk']; ?>, '<?php echo htmlspecialchars($row['nama_produk']); ?>', <?php echo $row['harga']; ?>)">
                                + Cart
                            </button>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px 0;">Belum ada produk digital yang aktif saat ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
    <script>
        function ubahAngka(btn, arah, maxStok) {
            const input = btn.parentElement.querySelector('.qty-input');
            let nilaiSekarang = parseInt(input.value) || 1;
            let nilaiBaru = nilaiSekarang + arah;

            if (nilaiBaru < 1) nilaiBaru = 1;
            if (maxStok && nilaiBaru > maxStok) nilaiBaru = maxStok;

            input.value = nilaiBaru;
        }

        function prosesKeranjang(id, nama, harga) {
            const qty = parseInt(document.getElementById('qty-' + id).value) || 1;
            tambahKeKeranjangDenganQty(id, nama, harga, qty);
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".hero-title", { duration: 1, y: 40, opacity: 0, ease: "power3.out" });
                // Animasi GSAP disesuaikan dengan class baru agar mulus dan solid
                gsap.from(".sleek-card-index", { duration: 0.5, y: 20, opacity: 1, stagger: 0.06, delay: 0.2, ease: "power2.out" });
            }
        });
    </script>
</body>

</html>