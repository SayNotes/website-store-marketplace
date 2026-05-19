<?php
include 'config/database.php';

$keyword = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

// Query pencarian ganda (Nama Produk ATAU Nama Toko Penjual)
$query = "SELECT produk.*, penjual.nama_toko 
          FROM produk 
          JOIN penjual ON produk.id_penjual = penjual.id_penjual 
          WHERE (produk.nama_produk LIKE '%$keyword%' 
          OR penjual.nama_toko LIKE '%$keyword%')
          AND penjual.status = 'aktif' AND produk.stok > 0
          ORDER BY produk.id_produk DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian: "<?php echo htmlspecialchars($keyword); ?>"</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* Base halaman Dark Mode Cyberpunk */
        body {
            background-color: #0b0b0c !important;
            color: #ffffff !important;
        }

        .search-page-container {
            padding-top: 40px;
            padding-bottom: 80px;
            position: relative;
            z-index: 10;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 28px;
            padding: 30px 0;
        }

        /* CARD STYLE SOLID: Menjamin semua kartu stabil warnanya, tidak memudar sebelah */
        .sleek-card {
            background: #141419 !important; 
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 24px !important;
            padding: 18px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            position: relative !important;
            opacity: 1 !important; 
            visibility: visible !important;
            transition: all 0.3s ease;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .sleek-card:hover {
            transform: translateY(-6px);
            border-color: #a855f7 !important;
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.25);
        }

        .sleek-card .preview-wrapper {
            width: 100%;
            height: 165px;
            border-radius: 16px;
            background-size: cover;
            background-position: center;
            background-color: #1e1e24 !important;
            background-image: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(30, 30, 36, 1)) !important; 
            position: relative;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sleek-card .floating-price {
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

        .sleek-card .sparkle-badge {
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

        .sleek-card .product-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff !important;
            margin-bottom: 6px;
            line-height: 1.4;
            display: block;
        }

        .sleek-card .seller-tag {
            font-size: 13px;
            color: #9ca3af !important;
            margin-bottom: 20px;
        }

        .sleek-card .seller-tag span {
            color: #c084fc !important;
            font-weight: 600;
        }

        .sleek-card .action-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
        }

        .sleek-card .btn-cart-minimal {
            flex: 1;
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            padding: 11px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .sleek-card .btn-cart-minimal:hover {
            background: #a855f7 !important;
            border-color: #a855f7 !important;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.4);
        }

        .quantity-counter {
            display: flex;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            background: #1e1e24 !important;
            border-radius: 12px;
            padding: 4px;
        }

        .count-btn {
            background: none;
            border: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            padding: 4px 10px;
            color: #ffffff !important;
        }

        .qty-input {
            border: none;
            background: none;
            text-align: center;
            width: 24px;
            font-weight: 600;
            color: #ffffff !important;
            outline: none;
        }
    </style>
</head>
<body>

    <div class="grid-bg"></div>

    <header>
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Market</span><span class="purple">Inspired</span>
            </div>
            
            <form action="pencarian.php" method="GET" class="search-box">
                <input type="text" name="query" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari UI kit, template, kreator..." required>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                    </svg>
                </button>
            </form>

            <div class="nav-actions">
                <a href="index.php" class="nav-link" style="color:var(--text-muted); font-size:14px; font-weight:600; text-decoration:none; margin-right:15px; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-muted)'">← Beranda</a>
                
                <a href="keranjang.php" class="nav-icon-link" title="Keranjang Belanja">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="container search-page-container">
        <h2 style="font-size: 13px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">Hasil Pencarian untuk:</h2>
        <h1 style="font-size: 34px; font-weight: 800; color: #ffffff; margin-bottom: 35px;">
            <span style="background: linear-gradient(45deg, #a855f7, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">"<?php echo htmlspecialchars($keyword); ?>"</span>
        </h1>

        <div class="products-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    
                    <div class="sleek-card">
                        <div>
                            <?php 
                                $pathFoto = !empty($row['foto']) ? "assets/img/" . $row['foto'] : "";
                                $inlineStyle = !empty($pathFoto) && file_exists($pathFoto) ? "background-image: url('$pathFoto');" : "";
                            ?>
                            <div class="preview-wrapper" style="<?php echo $inlineStyle; ?>">
                                <div class="floating-price">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </div>
                                <div class="sparkle-badge">✦</div>
                            </div>
                            
                            <div class="card-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                                <div class="seller-tag">
                                    by <span><?php echo htmlspecialchars($row['nama_toko']); ?></span>
                                </div>
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
                <div style="grid-column: 1/-1; text-align: center; padding: 70px 20px; background: #141419; border: 1px solid rgba(255,255,255,0.06); border-radius: 24px;">
                    <p style="color: #9ca3af; margin-bottom: 20px; font-size: 15px;">Tidak menemukan produk digital atau kreator dengan nama tersebut.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

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
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            let itemAda = keranjang.find(item => item.id === id);
            
            if (itemAda) { 
                itemAda.jumlah += qty; 
            } else { 
                keranjang.push({ id: id, nama: nama, harga: harga, jumlah: qty }); 
            }
            
            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            alert(`⚡ Sukses! ${qty} item "${nama}" berhasil dimasukkan ke keranjang.`);
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".sleek-card", { duration: 0.4, y: 15, opacity: 1, ease: "power2.out" });
            }
        });
    </script>
</body>
</html>