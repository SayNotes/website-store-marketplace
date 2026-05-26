<?php
include 'koneksi.php';

$aksi = $_GET['aksi'];
$id   = $_GET['id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($aksi == 'tambah') {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: index.php");
}

if ($aksi == 'hapus') {
    unset($_SESSION['cart'][$id]);
    header("Location: keranjang.php");
}
exit;
?>

