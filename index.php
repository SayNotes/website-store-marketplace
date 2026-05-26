<?php
// Hubungkan ke database mp_purple kamu di Laragon
require_once __DIR__ . '/config/database.php';

// --- LOGIKA KELOLA CAROUSEL BANNER ---
$query_carousel = "SELECT * FROM carousel ORDER BY id_carousel DESC";
$result_carousel = mysqli_query($conn, $query_carousel);
$carousel_images = [];

if ($result_carousel && mysqli_num_rows($result_carousel) > 0) {
    while ($row = mysqli_fetch_assoc($result_carousel)) {
        $carousel_images[] = 'assets/img/carousel/' . $row['foto'];
    }
} else {
    // DEFAULT IMAGE: Menggunakan placeholder gambar modern bertema dark abstrak digital jika database kosong
    $carousel_images = [
        'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1200&h=300&fit=crop',
        'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?q=80&w=1200&h=300&fit=crop'
    ];
}

// --- AMBIL DATA KATEGORI ---
$query_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC"; 
$result_kategori = mysqli_query($conn, $query_kategori);

// --- LOGIKA FILTER KATEGORI & AMBIL DATA PRODUK ---
$kategori_terpilih = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';

$query = "SELECT produk.*, penjual.nama_toko 
          FROM produk 
          JOIN penjual ON produk.id_penjual = penjual.id_penjual 
          WHERE penjual.status = 'aktif' AND produk.stok > 0";

// Jika user memilih kategori tertentu, tambahkan kondisi WHERE
if (!empty($kategori_terpilih)) {
    $query .= " AND produk.id_kategori = '$kategori_terpilih'"; 
}

$query .= " ORDER BY produk.id_produk DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamba Market</title>
    <link rel="stylesheet" href="assets/css/category-style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>

    <div class="grid-overlay" style="opacity: 0.15;"></div>
    <div class="purple-bokeh"></div>

    <header>
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Hamba</span><span class="purple">Market</span>
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
                </button>

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
            <div class="hero-section-title">
                <div class="div-hero-section">
                    <img src="assets/img/icon-genshin.png" alt="Icon Game" class="active">
                    <img src="assets/img/icon-valorant.png" alt="Icon Game">
                    <img src="assets/img/icon-pubg.png" alt="Icon Game">
                    <img src="assets/img/icon-cs.png" alt="Icon Game">
                </div>

                <div>
                    <h1 class="hero-title" style="min-height: 90px;">
                        <span class="white">Mau Cari Apa?</span><br>
                        <span class="purple-text" id="typing-text"></span><span class="typing-cursor">|</span>
                    </h1>
                    <p class="hero-subtitle">Solusi cepat, kualitas terjamin.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="container resources-section">
        <div class="carousel-box-wrapper">
            <div class="carousel-container">
                <div class="carousel-slide">
                    <?php foreach ($carousel_images as $index => $img): ?>
                        <div class="carousel-item">
                            <img src="<?php echo $img; ?>" alt="Banner <?php echo $index + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="carousel-btn prev-btn">&#10094;</button>
                <button type="button" class="carousel-btn next-btn">&#10095;</button>

                <div class="carousel-indicators">
                    <?php foreach ($carousel_images as $index => $img): ?>
                        <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>"
                            onclick="currentSlide(<?php echo $index; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <h2 class="section-title">Katalog Produk</h2>

        <div class="category-container"
            style="display: flex !important; gap: 12px !important; overflow-x: auto !important; padding: 15px 0 25px 0 !important; scrollbar-width: none !important;">

            <a href="index.php" class="category-badge <?php echo empty($kategori_terpilih) ? 'active' : ''; ?>">
                Semua
            </a>

            <?php if ($result_kategori && mysqli_num_rows($result_kategori) > 0): ?>
                <?php while ($kat = mysqli_fetch_assoc($result_kategori)):
                    $is_active = ($kategori_terpilih == $kat['id_kategori']);
                    ?>
                    <a href="index.php?kategori=<?php echo $kat['id_kategori']; ?>"
                        class="category-badge <?php echo $is_active ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </a>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="products-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $foto = $row['foto'];
                    if (empty($foto)) {
                        preg_match('/\[(.*?)\]/', $row['nama_produk'], $matches);
                        $text = !empty($matches[1]) ? urlencode($matches[1]) : 'Product';
                        $image_url = "https://placehold.co/400x400/120626/E0AAFF?font=montserrat&text=" . $text;
                    } else {
                        $image_url = (strpos($foto, 'http://') === 0 || strpos($foto, 'https://') === 0) ? $foto : 'assets/img/' . $foto;
                    }
                    ?>

                    <div class="sleek-card-index">
                        <div>
                            <div class="preview-wrapper" style="background-image: url('<?php echo $image_url; ?>')">
                                <div class="floating-price">
                                    <img src="assets/img/coin.png" alt="coin" class="money-icon">
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
                                <input type="number" class="qty-input" id="qty-<?php echo $row['id_produk']; ?>" value="1"
                                    min="1" max="<?php echo $row['stok']; ?>" readonly>
                                <button type="button" class="count-btn"
                                    onclick="ubahAngka(this, 1, <?php echo $row['stok']; ?>)">+</button>
                            </div>

                            <button class="btn-cart-minimal"
                                onclick="prosesKeranjang(<?php echo $row['id_produk']; ?>, <?php echo $row['id_penjual']; ?>, '<?php echo htmlspecialchars($row['nama_produk'], ENT_QUOTES); ?>', <?php echo $row['harga']; ?>)">
                                + Cart
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px 0;">Belum ada
                    produk digital yang aktif di kategori ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
    <script>
        // --- LOGIK SLIDER BANNER CAROUSEL ---
        let currentIndex = 0;
        const slideContainer = document.querySelector('.carousel-slide');
        const items = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.indicator');

        function showSlide(index) {
            if (index >= items.length) currentIndex = 0;
            else if (index < 0) currentIndex = items.length - 1;
            else currentIndex = index;

            const offset = -currentIndex * 100;
            slideContainer.style.transform = `translateX(${offset}%)`;

            indicators.forEach((ind, i) => {
                ind.classList.toggle('active', i === currentIndex);
            });
        }

        document.querySelector('.next-btn').addEventListener('click', () => { showSlide(currentIndex + 1); });
        document.querySelector('.prev-btn').addEventListener('click', () => { showSlide(currentIndex - 1); });
        function currentSlide(index) { showSlide(index); }

        let autoSlide = setInterval(() => { showSlide(currentIndex + 1); }, 5000);
        document.querySelector('.carousel-container').addEventListener('mouseenter', () => clearInterval(autoSlide));
        document.querySelector('.carousel-container').addEventListener('mouseleave', () => {
            autoSlide = setInterval(() => { showSlide(currentIndex + 1); }, 5000);
        });

        // Kontrol Kuantitas Produk
        function ubahAngka(btn, arah, maxStok) {
            const input = btn.parentElement.querySelector('.qty-input');
            let nilaiSekarang = parseInt(input.value) || 1;
            let nilaiBaru = nilaiSekarang + arah;

            if (nilaiBaru < 1) nilaiBaru = 1;
            if (maxStok && nilaiBaru > maxStok) nilaiBaru = maxStok;

            input.value = nilaiBaru;
        }

        // UPDATE: Menerima dan meneruskan id_penjual
        function prosesKeranjang(id, idPenjual, nama, harga) {
            const qty = parseInt(document.getElementById('qty-' + id).value) || 1;
            tambahKeKeranjangDenganQty(id, idPenjual, nama, harga, qty);
        }

        // UPDATE: Menyimpan struktur data lengkap ke localStorage (id_produk, id_penjual, nama, harga, jumlah)
        function tambahKeKeranjangDenganQty(id, idPenjual, nama, harga, qty) {
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            
            // Pencarian indeks diganti menggunakan id_produk agar lebih akurat dibanding nama string
            let itemIndex = keranjang.findIndex(item => item.id_produk === id);

            if (itemIndex > -1) {
                keranjang[itemIndex].jumlah += qty;
            } else {
                keranjang.push({ 
                    id_produk: id, 
                    id_penjual: idPenjual, 
                    nama: nama, 
                    harga: harga, 
                    jumlah: qty 
                });
            }

            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            alert(`🎉 Berhasil memasukkan ${qty} pcs "${nama}" ke keranjang belanja!`);
        }

        // --- TYPEWRITER EFFECT ---
        const kataKata = ["Marketplace Digital", "Cari Cepat tanpa Ribet", "Kualitas Premium", "Mudah & Terpercaya"];
        let indeksKata = 0;
        let indeksKarakter = 0;
        let sedangMenghapus = false;
        const targetElement = document.getElementById("typing-text");

        function jalankanEfekKetik() {
            const kataSaatIni = kataKata[indeksKata];

            if (sedangMenghapus) {
                targetElement.textContent = kataSaatIni.substring(0, indeksKarakter - 1);
                indeksKarakter--;
            } else {
                targetElement.textContent = kataSaatIni.substring(0, indeksKarakter + 1);
                indeksKarakter++;
            }

            let kecepatan = 100;

            if (sedangMenghapus) {
                kecepatan /= 2;
            }

            if (!sedangMenghapus && indeksKarakter === kataSaatIni.length) {
                kecepatan = 2000;
                sedangMenghapus = true;
            }
            else if (sedangMenghapus && indeksKarakter === 0) {
                sedangMenghapus = false;
                indeksKata = (indeksKata + 1) % kataKata.length;
                kecepatan = 400;
            }

            setTimeout(jalankanEfekKetik, kecepatan);
        }

        // Animasi GSAP Standar Saat Halaman Pertama Kali Dibuka
        document.addEventListener("DOMContentLoaded", () => {
            if (window.gsap) {
                gsap.from(".carousel-box-wrapper", { duration: 0.8, y: -20, opacity: 0, ease: "power3.out", delay: 0.1 });
                gsap.from(".sleek-card-index", { duration: 0.5, y: 20, opacity: 1, stagger: 0.06, delay: 0.3, ease: "power2.out" });
            }

            setTimeout(jalankanEfekKetik, 500);
        });
    </script>
    <script src="assets/js/animasi-ikon.js"></script>
</body>

</html>