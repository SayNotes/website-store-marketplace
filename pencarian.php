<?php
include 'config/database.php';

$keyword = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

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
</head>
<body>

    <div class="grid-bg"></div>
    <div class="purple-bokeh"></div>

    <header>
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Market</span><span class="purple">Inspired</span>
            </div>

            <form action="pencarian.php" method="GET" class="search-box">
                <input type="text" name="query"
                    value="<?php echo htmlspecialchars($keyword); ?>"
                    placeholder="Cari UI kit, template, kreator..." required>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z"/>
                    </svg>
                </button>
            </form>

            <div class="nav-actions">
                <a href="index.php" class="nav-link">← Beranda</a>
                <a href="keranjang.php" class="nav-icon-link" title="Keranjang Belanja">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.8" stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="container search-page-container">
        <p class="search-label">Hasil Pencarian untuk:</p>
        <h1 class="search-keyword">
            <span>"<?php echo htmlspecialchars($keyword); ?>"</span>
        </h1>

        <div class="products-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php
                    $pathFoto    = !empty($row['foto']) ? "assets/img/" . $row['foto'] : "";
                    $bgStyle     = (!empty($pathFoto) && file_exists($pathFoto))
                                   ? "background-image: url('{$pathFoto}');"
                                   : "";
                    ?>

                    <div class="sleek-card">
                        <div>
                            <div class="preview-wrapper" style="<?php echo $bgStyle; ?>">
                                <div class="floating-price">
                                    <img src="assets/img/coin.png" alt="coin" class="money-icon">
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
                                <input type="number" class="qty-input"
                                    id="qty-<?php echo $row['id_produk']; ?>"
                                    value="1" min="1" max="<?php echo $row['stok']; ?>" readonly>
                                <button type="button" class="count-btn"
                                    onclick="ubahAngka(this, 1, <?php echo $row['stok']; ?>)">+</button>
                            </div>
                            <button class="btn-cart-minimal"
                                onclick="prosesKeranjang(
                                    <?php echo $row['id_produk']; ?>,
                                    '<?php echo htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>',
                                    <?php echo $row['harga']; ?>)">
                                + Cart
                            </button>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="search-empty">
                    <p>Tidak menemukan produk digital atau kreator dengan nama tersebut.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function ubahAngka(btn, arah, maxStok) {
            const input = btn.parentElement.querySelector('.qty-input');
            let val = Math.max(1, (parseInt(input.value) || 1) + arah);
            if (maxStok) val = Math.min(val, maxStok);
            input.value = val;
        }

        function prosesKeranjang(id, nama, harga) {
            const qty = parseInt(document.getElementById('qty-' + id).value) || 1;
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            let item = keranjang.find(i => i.id === id);
            if (item) { item.jumlah += qty; }
            else       { keranjang.push({ id, nama, harga, jumlah: qty }); }
            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            alert(`⚡ Sukses! ${qty} item "${nama}" berhasil dimasukkan ke keranjang.`);
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".sleek-card", { duration: 0.4, y: 15, opacity: 0, stagger: 0.05, ease: "power2.out" });
            }
        });
    </script>
</body>
</html>