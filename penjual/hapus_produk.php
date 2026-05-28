<?php
session_start();

// Validasi otentikasi login
if (!isset($_SESSION['penjual_id']) || empty($_SESSION['penjual_id'])) { 
    header("Location: login.php");
    exit; 
}

include '../config/database.php';

$id_penjual = intval($_SESSION['penjual_id']);
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verifikasi kepemilikan aset terlebih dahulu sebelum mengeksekusi aksi hapus
$query_cek = "SELECT foto FROM produk WHERE id_produk = $id_produk AND id_penjual = $id_penjual";
$result_cek = mysqli_query($conn, $query_cek);

if (mysqli_num_rows($result_cek) > 0) {
    $produk = mysqli_fetch_assoc($result_cek);
    
    // Hapus file gambar asli di direktori lokal agar server tidak kepenuhan cache sampah
    if (!empty($produk['foto'])) {
        $path_berkas = "../assets/img/" . $produk['foto'];
        if (file_exists($path_berkas)) {
            unlink($path_berkas);
        }
    }
    
    // Hapus record dari database
    $query_hapus = "DELETE FROM produk WHERE id_produk = $id_produk AND id_penjual = $id_penjual";
    
    if (mysqli_query($conn, $query_hapus)) {
        header("Location: dashboard.php?msg=hapus_sukses");
        exit;
    }
}

// Kembalikan ke dashboard dengan pesan error jika id tidak valid/bukan miliknya
header("Location: dashboard.php?msg=gagal");
exit;
?>