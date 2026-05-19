<?php
include 'koneksi.php';

if(empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// 1. Buat Pelanggan Guest Otomatis
$nama_guest = "GUEST_" . rand(1000, 9999);
mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan) VALUES ('$nama_guest')");
$id_pelanggan = mysqli_insert_id($conn);

// 2. Hitung Total Pembayaran
$metode_bayar = $_POST['pembayaran'];
$total_keseluruhan = 0;
foreach ($_SESSION['cart'] as $id_barang => $jumlah) {
    $res_barang = mysqli_query($conn, "SELECT harga FROM barang WHERE id_barang = '$id_barang'");
    $b = mysqli_fetch_assoc($res_barang);
    $total_keseluruhan += ($b['harga'] * $jumlah);
}

// 3. Insert ke tabel Transaksi Utama
mysqli_query($conn, "INSERT INTO transaksi (id_pelanggan, total_bayar, metode_pembayaran) VALUES ('$id_pelanggan', '$total_keseluruhan', '$metode_bayar')");
$id_transaksi = mysqli_insert_id($conn);

// 4. Insert Detail & Potong Stok
foreach ($_SESSION['cart'] as $id_barang => $jumlah) {
    $res_barang = mysqli_query($conn, "SELECT harga, stok FROM barang WHERE id_barang = '$id_barang'");
    $b = mysqli_fetch_assoc($res_barang);
    $subtotal = $b['harga'] * $jumlah;

    mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) VALUES ('$id_transaksi', '$id_barang', '$jumlah', '$subtotal')");

    $stok_baru = $b['stok'] - $jumlah;
    mysqli_query($conn, "UPDATE barang SET stok = '$stok_baru' WHERE id_barang = '$id_barang'");
}

unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Transaksi Sukses</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh;">
    <div style="border: 1px solid var(--border-color); padding: 40px; background: var(--bg-surface); text-align: center;">
        <h2 style="color: var(--accent-yellow);">// DATA_MUTASI_SUKSES_#<?= $id_transaksi ?></h2>
        <br>
        <p style="color: var(--text-muted);">Pembayaran via [<?= $metode_bayar ?>] sebesar <strong>Rp <?= number_format($total_keseluruhan) ?></strong> berhasil diarsip.</p>
        <br>
        <a href="index.php" style="background:var(--accent-yellow); color:black; padding:10px 20px; text-decoration:none; font-weight:bold; display:inline-block;">Kembali ke Utama</a>
    </div>
</body>
</html>