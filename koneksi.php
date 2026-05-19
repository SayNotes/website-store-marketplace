<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "toko_online_minimal"; // Sesuaikan dengan nama database baru kita

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
session_start();
?>