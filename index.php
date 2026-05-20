<?php
// Hubungkan ke database
require_once __DIR__ . '/config/database.php';

// --- LOGIKA KELOLA CAROUSEL BANNER ---
$query_carousel = "SELECT * FROM carousel ORDER BY id_carousel DESC";
$result_carousel = mysqli_query($conn, $query_carousel);
$carousel_images = [];

if ($result_carousel && mysqli_num_rows($result_carousel) > 0) {
    while ($row = mysqli_fetch_assoc($result_carousel)) {
        $carousel_images[] = [
            'src'   => 'assets/img/carousel/' . $row['foto'],
            'judul' => $row['judul'] ?? 'Produk Digital Premium',
            'sub'   => $row['subjudul'] ?? 'Temukan karya terbaik dari kreator terpilih'
        ];
    }
} else {
    $carousel_images = [
        ['src' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1400&h=500&fit=crop', 'judul' => 'UI Kit & Template Premium', 'sub' => 'Koleksi desain eksklusif siap pakai'],
        ['src' => 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?q=80&w=1400&h=500&fit=crop', 'judul' => 'Aset Digital Berkualitas', 'sub' => 'Dari kreator terpercaya Indonesia'],
        ['src' => 'https://images.unsplash.com/photo-1558655146-9f40138edfeb?q=80&w=1400&h=500&fit=crop', 'judul' => 'Source Code & Aplikasi', 'sub' => 'Solusi cepat, harga terjangkau'],
    ];
}

// Ambil data kategori (jika ada tabel kategori)
$kategori_list = [];
$q_kat = "SELECT * FROM kategori ORDER BY nama_kategori ASC LIMIT 8";
$r_kat = mysqli_query($conn, $q_kat);
if ($r_kat && mysqli_num_rows($r_kat) > 0) {
    while ($k = mysqli_fetch_assoc($r_kat)) $kategori_list[] = $k;
} else {
    $kategori_list = [
        ['id_kategori'=>1,'nama_kategori'=>'UI Kit','ikon'=>'🎨'],
        ['id_kategori'=>2,'nama_kategori'=>'Template Web','ikon'=>'🌐'],
        ['id_kategori'=>3,'nama_kategori'=>'Source Code','ikon'=>'💻'],
        ['id_kategori'=>4,'nama_kategori'=>'Desain Grafis','ikon'=>'✏️'],
        ['id_kategori'=>5,'nama_kategori'=>'Font & Tipografi','ikon'=>'🔤'],
        ['id_kategori'=>6,'nama_kategori'=>'Foto & Aset','ikon'=>'📷'],
        ['id_kategori'=>7,'nama_kategori'=>'Video & Animasi','ikon'=>'🎬'],
        ['id_kategori'=>8,'nama_kategori'=>'Plugin & Addon','ikon'=>'🔌'],
    ];
}

// Ambil produk featured (terbaru)
$query = "SELECT produk.*, penjual.nama_toko
          FROM produk
          JOIN penjual ON produk.id_penjual = penjual.id_penjual
          WHERE penjual.status = 'aktif' AND produk.stok > 0
          ORDER BY produk.id_produk DESC";
$result = mysqli_query($conn, $query);

// Ambil produk deals/promo (harga terendah)
$query_deals = "SELECT produk.*, penjual.nama_toko
                FROM produk
                JOIN penjual ON produk.id_penjual = penjual.id_penjual
                WHERE penjual.status = 'aktif' AND produk.stok > 0
                ORDER BY produk.harga ASC LIMIT 4";
$result_deals = mysqli_query($conn, $query_deals);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspired Market - Marketplace Digital Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #2E7D32;
            --primary-light: #43A047;
            --primary-pale: #E8F5E9;
            --primary-dark: #1B5E20;
            --accent: #FF6F00;
            --accent-light: #FFA000;
            --accent-pale: #FFF8E1;
            --text-dark: #1A1A2E;
            --text-mid: #4A4A6A;
            --text-light: #8888AA;
            --border: #E8E8F0;
            --surface: #FFFFFF;
            --bg: #F5F6FA;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.06);
            --shadow-md: 0 6px 24px rgba(0,0,0,0.09);
            --shadow-lg: 0 16px 48px rgba(0,0,0,0.13);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            font-size: 14px;
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }
        img { display: block; max-width: 100%; }

        /* ===== ANNOUNCEMENT BAR ===== */
        .announcement-bar {
            background: var(--primary-dark);
            color: #fff;
            text-align: center;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        .announcement-bar a {
            color: #FFD54F;
            font-weight: 700;
            margin-left: 8px;
            text-decoration: underline;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
        }
        .topbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .logo {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
        }
        .logo .em { color: var(--accent); }
        .logo .arket { color: var(--primary); }

        .logo-leaf {
            display: inline-block;
            margin-right: 2px;
        }

        .support-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-mid);
            font-size: 13px;
            white-space: nowrap;
        }
        .support-info strong { color: var(--text-dark); font-size: 15px; }
        .support-icon {
            width: 38px; height: 38px;
            background: var(--primary-pale);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 18px;
        }

        .search-wrap {
            flex: 1;
            display: flex;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
            max-width: 640px;
        }
        .search-cat {
            padding: 0 14px;
            background: var(--bg);
            border: none;
            border-right: 1px solid var(--border);
            font-family: inherit;
            font-size: 13px;
            color: var(--text-dark);
            cursor: pointer;
            min-width: 130px;
        }
        .search-cat:focus { outline: none; }
        .search-input {
            flex: 1;
            border: none;
            padding: 10px 14px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
        }
        .search-input:focus { outline: none; }
        .search-input::placeholder { color: var(--text-light); }
        .search-btn {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 0 22px;
            font-family: inherit;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-btn:hover { background: var(--accent-light); }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .action-btn {
            position: relative;
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1.5px solid var(--border);
            cursor: pointer;
            color: var(--text-dark);
            font-size: 19px;
            transition: all 0.2s;
            background: #fff;
        }
        .action-btn:hover { border-color: var(--primary); color: var(--primary); }
        .badge {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .account-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            background: #fff;
            transition: all 0.2s;
            color: var(--text-dark);
        }
        .account-btn:hover { border-color: var(--primary); color: var(--primary); }

        /* ===== NAV ===== */
        .main-nav {
            background: var(--primary);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
        }
        .nav-link {
            color: rgba(255,255,255,0.88);
            padding: 13px 18px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            border-bottom: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-bottom-color: #FFD54F;
        }
        .nav-link .arrow { font-size: 10px; opacity: 0.7; }
        .nav-social {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .social-btn {
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            transition: all 0.2s;
        }
        .social-btn:hover { background: rgba(255,255,255,0.15); color: #fff; }

        /* ===== MAIN LAYOUT ===== */
        .main-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px 24px;
        }

        /* ===== HERO ROW ===== */
        .hero-row {
            display: grid;
            grid-template-columns: 220px 1fr 260px;
            gap: 16px;
            margin-bottom: 32px;
        }

        /* Sidebar Categories */
        .sidebar-cats {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .sidebar-title {
            background: var(--primary);
            color: #fff;
            padding: 14px 18px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .sidebar-cat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-mid);
            font-size: 13.5px;
            transition: all 0.15s;
            cursor: pointer;
        }
        .sidebar-cat-item:last-child { border-bottom: none; }
        .sidebar-cat-item:hover {
            background: var(--primary-pale);
            color: var(--primary);
            padding-left: 22px;
        }
        .sidebar-cat-item .cat-icon {
            font-size: 17px;
            width: 28px;
            text-align: center;
        }

        /* Carousel */
        .carousel-wrap {
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            position: relative;
        }
        .carousel-track {
            display: flex;
            width: 100%;
            height: 340px;
            transition: transform 0.55s cubic-bezier(.25,1,.5,1);
        }
        .carousel-slide-item {
            min-width: 100%;
            height: 100%;
            position: relative;
        }
        .carousel-slide-item img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .carousel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0,50,0,0.7) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 32px 40px;
        }
        .carousel-tag {
            background: var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
            width: fit-content;
        }
        .carousel-title {
            font-family: 'Sora', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 10px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
        .carousel-sub {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            margin-bottom: 22px;
        }
        .carousel-cta {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 11px 26px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            width: fit-content;
        }
        .carousel-cta:hover { background: var(--accent-light); }
        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);
            border: none;
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 16px;
            color: var(--text-dark);
            box-shadow: var(--shadow-sm);
            z-index: 5;
            transition: all 0.2s;
        }
        .carousel-nav-btn:hover { background: var(--primary); color: #fff; }
        .c-prev { left: 14px; }
        .c-next { right: 14px; }
        .carousel-dots {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 7px;
            z-index: 5;
        }
        .cdot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.45);
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        .cdot.active {
            background: #fff;
            width: 22px;
            border-radius: 4px;
        }

        /* Right Banner */
        .right-banner {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .mini-banner {
            border-radius: var(--radius-md);
            overflow: hidden;
            flex: 1;
            position: relative;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }
        .mini-banner img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .mini-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,111,0,0.82) 0%, rgba(255,160,0,0.5) 60%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 18px;
        }
        .mini-banner-tag {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .mini-banner-title {
            font-family: 'Sora', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 10px;
        }
        .mini-banner-cta {
            background: #fff;
            color: var(--accent);
            font-weight: 700;
            font-size: 12px;
            padding: 7px 16px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
        }

        /* ===== FEATURED CATEGORIES ===== */
        .section { margin-bottom: 40px; }
        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .section-label {
            font-family: 'Sora', sans-serif;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            font-style: italic;
            margin-bottom: 4px;
        }
        .section-title {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
        }
        .view-all-link {
            color: var(--primary);
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 18px;
            border: 2px solid var(--primary);
            border-radius: 6px;
            transition: all 0.2s;
        }
        .view-all-link:hover { background: var(--primary); color: #fff; }

        .feat-cats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
        }
        .feat-cat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            overflow: hidden;
            text-align: center;
            cursor: pointer;
            border: 1.5px solid var(--border);
            transition: all 0.25s;
            box-shadow: var(--shadow-sm);
        }
        .feat-cat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-md);
        }
        .cat-arch {
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 0 60% 60%;
            margin-bottom: 14px;
            position: relative;
            overflow: hidden;
        }
        .feat-cat-card:nth-child(1) .cat-arch { background: #F3E5F5; }
        .feat-cat-card:nth-child(2) .cat-arch { background: #FBE9E7; }
        .feat-cat-card:nth-child(3) .cat-arch { background: #FCE4EC; }
        .feat-cat-card:nth-child(4) .cat-arch { background: #E3F2FD; }
        .feat-cat-card:nth-child(5) .cat-arch { background: #E8F5E9; }
        .feat-cat-card:nth-child(6) .cat-arch { background: #FFF8E1; }
        .cat-arch-icon { font-size: 48px; }
        .feat-cat-name {
            font-weight: 700;
            font-size: 13px;
            color: var(--text-dark);
            padding: 0 12px 16px;
        }

        /* ===== DEALS SECTION ===== */
        .deals-section {
            background: #fff;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: 320px 1fr;
            margin-bottom: 40px;
        }
        .deals-left {
            background: linear-gradient(160deg, var(--primary-pale) 0%, #fff 100%);
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            border-right: 1px solid var(--border);
            overflow: hidden;
        }
        .deals-left::before {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(46,125,50,0.07);
        }
        .deals-tag {
            color: var(--primary);
            font-style: italic;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            margin-bottom: 6px;
        }
        .deals-title {
            font-family: 'Sora', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.25;
            margin-bottom: 20px;
        }
        .deals-countdown-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .countdown-row {
            display: flex;
            gap: 10px;
            margin-bottom: 28px;
        }
        .count-box {
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            min-width: 56px;
            text-align: center;
        }
        .count-num {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
            display: block;
        }
        .count-label {
            font-size: 10px;
            font-weight: 600;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            display: block;
        }
        .view-all-deals {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            text-decoration: none;
            transition: background 0.2s;
        }
        .view-all-deals:hover { background: var(--accent-light); }

        .deals-products {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
        }
        .deal-card {
            padding: 28px 24px;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            cursor: pointer;
        }
        .deal-card:hover { background: var(--bg); }
        .deal-card:nth-child(2n) { border-right: none; }
        .deal-card:nth-child(3), .deal-card:nth-child(4) { border-bottom: none; }
        .deal-img-wrap {
            height: 140px;
            background: var(--bg);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            overflow: hidden;
        }
        .deal-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .deal-cats {
            font-size: 11px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .deal-name {
            font-weight: 700;
            font-size: 14.5px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .deal-price-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .deal-price {
            color: var(--accent);
            font-weight: 800;
            font-size: 16px;
        }
        .deal-price-old {
            color: var(--text-light);
            font-size: 13px;
            text-decoration: line-through;
        }
        .deal-add-btn {
            margin-top: 12px;
            width: 100%;
            background: #fff;
            border: 1.5px solid var(--border);
            color: var(--text-dark);
            padding: 9px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .deal-add-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-pale);
        }

        /* ===== PRODUCTS GRID ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        .prod-card {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all 0.25s;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
        }
        .prod-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-md);
        }
        .prod-img-wrap {
            height: 170px;
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }
        .prod-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .prod-card:hover .prod-img-wrap img { transform: scale(1.05); }
        .prod-badge {
            position: absolute;
            top: 10px; left: 10px;
            background: var(--accent);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .prod-wish {
            position: absolute;
            top: 10px; right: 10px;
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .prod-wish:hover { color: #e53935; background: #fff; }
        .prod-body {
            padding: 14px 16px;
        }
        .prod-toko {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .prod-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 6px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .prod-stok {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 12px;
        }
        .prod-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .prod-price {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: var(--accent);
        }
        .prod-actions {
            display: flex;
            gap: 6px;
        }
        .qty-wrap {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }
        .qty-btn {
            width: 28px; height: 30px;
            background: var(--bg);
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-mid);
            transition: background 0.15s;
            display: flex; align-items: center; justify-content: center;
        }
        .qty-btn:hover { background: var(--primary-pale); color: var(--primary); }
        .qty-val {
            width: 28px;
            text-align: center;
            border: none;
            background: none;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-dark);
            outline: none;
        }
        .add-cart-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0 14px;
            height: 34px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .add-cart-btn:hover { background: var(--primary-dark); }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px;
            color: var(--text-light);
        }
        .empty-state .e-icon { font-size: 48px; margin-bottom: 14px; }
        .empty-state p { font-size: 15px; }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 30px; right: 30px;
            background: var(--primary-dark);
            color: #fff;
            padding: 14px 22px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            transform: translateY(80px);
            opacity: 0;
            transition: all 0.35s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }

        /* ===== FOOTER ===== */
        footer {
            background: var(--primary-dark);
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 20px;
            font-size: 13px;
            margin-top: 10px;
        }
        footer strong { color: #fff; }

        @media (max-width: 1100px) {
            .hero-row { grid-template-columns: 200px 1fr; }
            .right-banner { display: none; }
            .feat-cats-grid { grid-template-columns: repeat(4, 1fr); }
            .products-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .hero-row { grid-template-columns: 1fr; }
            .sidebar-cats { display: none; }
            .deals-section { grid-template-columns: 1fr; }
            .feat-cats-grid { grid-template-columns: repeat(3, 1fr); }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .support-info { display: none; }
        }
        @media (max-width: 500px) {
            .feat-cats-grid { grid-template-columns: repeat(2, 1fr); }
            .products-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar">
    🎉 DAPATKAN DISKON 20% UNTUK SEMUA PRODUK MINGGU INI! <a href="pencarian.php">BELANJA SEKARANG →</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-inner">
        <div class="logo" onclick="window.location.href='index.php'">
            <span class="em">E</span><span class="arket">Market</span>
        </div>

        <div class="support-info">
            <div class="support-icon">📞</div>
            <div>
                <div style="font-size:11px;color:var(--text-light);">Dukungan Online 24/7</div>
                <strong>+62 812 3456 7890</strong>
            </div>
        </div>

        <form action="pencarian.php" method="GET" class="search-wrap">
            <select name="kategori" class="search-cat">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori_list as $kat): ?>
                    <option value="<?php echo $kat['id_kategori']; ?>"><?php echo htmlspecialchars($kat['nama_kategori']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="query" class="search-input" placeholder="Cari UI kit, template, kreator...">
            <button type="submit" class="search-btn">CARI</button>
        </form>

        <div class="topbar-actions">
            <button class="action-btn" title="Wishlist" onclick="window.location.href='wishlist.php'">
                🤍 <span class="badge">0</span>
            </button>
            <a href="keranjang.php" class="action-btn" title="Keranjang">
                🛒 <span class="badge" id="cart-count">0</span>
            </a>
            <a href="penjual/login.php" class="account-btn">👤 Akun</a>
        </div>
    </div>
</div>

<!-- MAIN NAV -->
<nav class="main-nav">
    <div class="nav-inner">
        <a href="index.php" class="nav-link active">🏠 Beranda <span class="arrow">▾</span></a>
        <a href="pencarian.php" class="nav-link">🛍️ Toko <span class="arrow">▾</span></a>
        <a href="#" class="nav-link">🔥 Promo <span class="arrow">▾</span></a>
        <a href="#" class="nav-link">📰 Blog <span class="arrow">▾</span></a>
        <a href="#" class="nav-link">⭐ Unggulan <span class="arrow">▾</span></a>
        <a href="#" class="nav-link">📄 Halaman <span class="arrow">▾</span></a>
        <a href="penjual/login.php" class="nav-link">🏪 Jadi Penjual <span class="arrow">▾</span></a>

        <div class="nav-social">
            <a href="#" class="social-btn" title="Facebook">f</a>
            <a href="#" class="social-btn" title="YouTube">▶</a>
            <a href="#" class="social-btn" title="Twitter">✕</a>
            <a href="#" class="social-btn" title="Instagram">◎</a>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="main-wrapper">

    <!-- HERO ROW: Sidebar + Carousel + Right Banner -->
    <div class="hero-row">

        <!-- Sidebar Kategori -->
        <aside class="sidebar-cats">
            <div class="sidebar-title">🗂 KATEGORI</div>
            <?php foreach ($kategori_list as $kat): ?>
                <a href="pencarian.php?kategori=<?php echo $kat['id_kategori']; ?>" class="sidebar-cat-item">
                    <span class="cat-icon"><?php echo $kat['ikon'] ?? '📁'; ?></span>
                    <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                </a>
            <?php endforeach; ?>
        </aside>

        <!-- Carousel Banner -->
        <div class="carousel-wrap">
            <div class="carousel-track" id="carousel-track">
                <?php foreach ($carousel_images as $i => $slide): ?>
                    <div class="carousel-slide-item">
                        <img src="<?php echo $slide['src']; ?>" alt="Banner <?php echo $i+1; ?>" loading="<?php echo $i===0?'eager':'lazy'; ?>">
                        <div class="carousel-overlay">
                            <span class="carousel-tag">✦ Produk Digital Terpilih</span>
                            <h2 class="carousel-title"><?php echo htmlspecialchars($slide['judul']); ?></h2>
                            <p class="carousel-sub"><?php echo htmlspecialchars($slide['sub']); ?></p>
                            <a href="pencarian.php" class="carousel-cta">Belanja Sekarang →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-nav-btn c-prev" onclick="moveCarousel(-1)">&#10094;</button>
            <button class="carousel-nav-btn c-next" onclick="moveCarousel(1)">&#10095;</button>
            <div class="carousel-dots" id="carousel-dots">
                <?php foreach ($carousel_images as $i => $slide): ?>
                    <button class="cdot <?php echo $i===0?'active':''; ?>" onclick="goSlide(<?php echo $i; ?>)"></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Mini Banners -->
        <div class="right-banner">
            <div class="mini-banner">
                <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=400&h=200&fit=crop" alt="Promo 1">
                <div class="mini-banner-overlay">
                    <div class="mini-banner-tag">Penawaran Spesial</div>
                    <div class="mini-banner-title">Diskon 50%<br>Source Code</div>
                    <a href="pencarian.php" class="mini-banner-cta">Belanja →</a>
                </div>
            </div>
            <div class="mini-banner">
                <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=400&h=200&fit=crop" alt="Promo 2">
                <div class="mini-banner-overlay" style="background:linear-gradient(135deg,rgba(46,125,50,0.82) 0%,rgba(67,160,71,0.5) 60%,transparent 100%)">
                    <div class="mini-banner-tag">Musim Panas</div>
                    <div class="mini-banner-title">UI Kit &amp;<br>Template Baru</div>
                    <a href="pencarian.php" class="mini-banner-cta" style="color:var(--primary)">Lihat →</a>
                </div>
            </div>
        </div>
    </div><!-- /hero-row -->

    <!-- FEATURED CATEGORIES -->
    <section class="section">
        <div class="section-header">
            <div>
                <div class="section-label">Kisah Kami</div>
                <h2 class="section-title">Kategori Unggulan</h2>
            </div>
            <a href="pencarian.php" class="view-all-link">Lihat Semua →</a>
        </div>
        <div class="feat-cats-grid">
            <?php
            $cat_icons_fallback = ['🎨','🍎','🥩','🥛','🧺','🍞'];
            foreach ($kategori_list as $idx => $kat):
                $icon = $kat['ikon'] ?? ($cat_icons_fallback[$idx % 6] ?? '📁');
            ?>
            <a href="pencarian.php?kategori=<?php echo $kat['id_kategori']; ?>" class="feat-cat-card">
                <div class="cat-arch">
                    <span class="cat-arch-icon"><?php echo $icon; ?></span>
                </div>
                <div class="feat-cat-name"><?php echo htmlspecialchars($kat['nama_kategori']); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- DEALS OF THE DAY -->
    <section class="deals-section">
        <div class="deals-left">
            <div class="deals-tag">Penawaran Hari Ini</div>
            <h2 class="deals-title">Grab the Best<br>Deal of The Week!</h2>
            <div class="deals-countdown-label">Berakhir Dalam:</div>
            <div class="countdown-row">
                <div class="count-box"><span class="count-num" id="cd-d">00</span><span class="count-label">Hari</span></div>
                <div class="count-box"><span class="count-num" id="cd-h">00</span><span class="count-label">Jam</span></div>
                <div class="count-box"><span class="count-num" id="cd-m">00</span><span class="count-label">Menit</span></div>
                <div class="count-box"><span class="count-num" id="cd-s">00</span><span class="count-label">Detik</span></div>
            </div>
            <a href="pencarian.php" class="view-all-deals">Lihat Semua →</a>
        </div>
        <div class="deals-products">
            <?php
            if ($result_deals && mysqli_num_rows($result_deals) > 0):
                while ($d = mysqli_fetch_assoc($result_deals)):
                    $img = !empty($d['foto']) ? 'assets/img/'.$d['foto'] : 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?q=80&w=300&h=140&fit=crop';
                    $harga_asli = $d['harga'] * 1.3;
            ?>
            <div class="deal-card">
                <div class="deal-img-wrap">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($d['nama_produk']); ?>">
                </div>
                <div class="deal-cats"><?php echo htmlspecialchars($d['nama_toko']); ?></div>
                <div class="deal-name"><?php echo htmlspecialchars($d['nama_produk']); ?></div>
                <div class="deal-price-row">
                    <span class="deal-price">Rp <?php echo number_format($d['harga'],0,',','.'); ?></span>
                    <span class="deal-price-old">Rp <?php echo number_format($harga_asli,0,',','.'); ?></span>
                </div>
                <button class="deal-add-btn" onclick="prosesKeranjang(<?php echo $d['id_produk']; ?>,'<?php echo htmlspecialchars($d['nama_produk']); ?>',<?php echo $d['harga']; ?>)">
                    + Tambah ke Keranjang
                </button>
            </div>
            <?php endwhile; else: ?>
            <div style="grid-column:1/-1;display:flex;align-items:center;justify-content:center;height:300px;color:var(--text-light);">
                Belum ada promo tersedia.
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ALL PRODUCTS -->
    <section class="section">
        <div class="section-header">
            <div>
                <div class="section-label">Koleksi Terbaru</div>
                <h2 class="section-title">Semua Produk Digital</h2>
            </div>
            <a href="pencarian.php" class="view-all-link">Lihat Semua →</a>
        </div>
        <div class="products-grid">
            <?php
            if ($result && mysqli_num_rows($result) > 0):
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)):
                    $img = !empty($row['foto']) ? 'assets/img/'.$row['foto'] : 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=400&h=200&fit=crop';
            ?>
            <div class="prod-card">
                <div class="prod-img-wrap">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" loading="lazy">
                    <span class="prod-badge">✦ Baru</span>
                    <button class="prod-wish" title="Wishlist">🤍</button>
                </div>
                <div class="prod-body">
                    <div class="prod-toko">🏪 <?php echo htmlspecialchars($row['nama_toko']); ?></div>
                    <div class="prod-name"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                    <div class="prod-stok">Stok: <?php echo $row['stok']; ?> pcs</div>
                    <div class="prod-footer">
                        <div class="prod-price">Rp <?php echo number_format($row['harga'],0,',','.'); ?></div>
                        <div class="prod-actions">
                            <div class="qty-wrap">
                                <button class="qty-btn" onclick="ubahAngka(this,-1)">−</button>
                                <input class="qty-val" type="number" id="qty-<?php echo $row['id_produk']; ?>" value="1" min="1" max="<?php echo $row['stok']; ?>" readonly>
                                <button class="qty-btn" onclick="ubahAngka(this,1,<?php echo $row['stok']; ?>)">+</button>
                            </div>
                            <button class="add-cart-btn" onclick="prosesKeranjang(<?php echo $row['id_produk']; ?>,'<?php echo addslashes(htmlspecialchars($row['nama_produk'])); ?>',<?php echo $row['harga']; ?>)">
                                🛒 Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="empty-state">
                <div class="e-icon">📦</div>
                <p>Belum ada produk aktif saat ini.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- TOAST NOTIFICATION -->
<div class="toast" id="toast">✅ Berhasil ditambahkan ke keranjang!</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> <strong>Inspired Market</strong> — Marketplace Digital Indonesia. All rights reserved.</p>
</footer>

<script src="assets/js/main.js"></script>
<script>
// ===== CAROUSEL =====
let ci = 0;
const track = document.getElementById('carousel-track');
const dots  = document.querySelectorAll('.cdot');
const total = dots.length;

function goSlide(idx) {
    if (idx < 0) idx = total - 1;
    if (idx >= total) idx = 0;
    ci = idx;
    track.style.transform = `translateX(-${ci * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === ci));
}
function moveCarousel(dir) { goSlide(ci + dir); }

let autoTimer = setInterval(() => moveCarousel(1), 5000);
track.closest('.carousel-wrap').addEventListener('mouseenter', () => clearInterval(autoTimer));
track.closest('.carousel-wrap').addEventListener('mouseleave', () => {
    autoTimer = setInterval(() => moveCarousel(1), 5000);
});

// ===== COUNTDOWN (7 hari dari sekarang) =====
const endTime = new Date().getTime() + 7 * 24 * 60 * 60 * 1000;
function updateCountdown() {
    const now  = new Date().getTime();
    const diff = Math.max(0, endTime - now);
    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((diff % (1000 * 60)) / 1000);
    document.getElementById('cd-d').textContent = String(d).padStart(2,'0');
    document.getElementById('cd-h').textContent = String(h).padStart(2,'0');
    document.getElementById('cd-m').textContent = String(m).padStart(2,'0');
    document.getElementById('cd-s').textContent = String(s).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

// ===== QUANTITY =====
function ubahAngka(btn, arah, maxStok) {
    const input = btn.parentElement.querySelector('.qty-val');
    let val = parseInt(input.value) || 1;
    val += arah;
    if (val < 1) val = 1;
    if (maxStok && val > maxStok) val = maxStok;
    input.value = val;
}

// ===== CART =====
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = '✅ ' + msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}
function prosesKeranjang(id, nama, harga) {
    const qEl = document.getElementById('qty-' + id);
    const qty  = qEl ? (parseInt(qEl.value) || 1) : 1;
    if (typeof tambahKeKeranjangDenganQty === 'function') {
        tambahKeKeranjangDenganQty(id, nama, harga, qty);
    }
    showToast(nama + ' ditambahkan ke keranjang!');
    const badge = document.getElementById('cart-count');
    if (badge) badge.textContent = (parseInt(badge.textContent)||0) + qty;
}
</script>
</body>
</html>