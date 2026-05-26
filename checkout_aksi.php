<?php
// Mengaktifkan buffer output agar jika ada warning PHP tidak merusak output JSON
ob_start();
session_start();

// Set header wajib agar browser tahu ini adalah data JSON
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan tidak dikenal pada server.'];

try {
    // 1. Cek apakah file database ada
    $db_path = __DIR__ . '/config/database.php';
    if (!file_exists($db_path)) {
        throw new Exception("File konfigurasi database tidak ditemukan di path: " . $db_path);
    }
    require_once $db_path;

    // 2. Cek apakah koneksi database ($conn) berhasil terhubung
    if (!isset($conn) || !$conn) {
        throw new Exception("Koneksi database gagal: " . mysqli_connect_error());
    }

    // 3. Ambil data JSON mentah yang dikirim oleh Fetch API JavaScript
    $inputRaw = file_get_contents('php://input');
    $keranjang = json_decode($inputRaw, true);

    if (empty($keranjang)) {
        throw new Exception("Gagal memproses data, keranjang belanja kosong.");
    }

    // Ambil nama pembeli dari session jika ada, kalau tidak ada pakai Guest
    $nama_pembeli = isset($_SESSION['pembeli_nama']) ? $_SESSION['pembeli_nama'] : 'Pembeli Guest';
    $nama_pembeli_clean = mysqli_real_escape_string($conn, $nama_pembeli);

    // Memulai transaksi database (Semua harus sukses, atau batal sama sekali)
    mysqli_begin_transaction($conn);

    foreach ($keranjang as $item) {
        // Validasi kelengkapan data yang dikirim dari localStorage
        if (!isset($item['id_produk']) || !isset($item['id_penjual']) || !isset($item['harga']) || !isset($item['jumlah'])) {
            throw new Exception("Struktur data produk di keranjang tidak lengkap. Pastikan id_produk dan id_penjual terekam.");
        }

        $id_produk    = intval($item['id_produk']);
        $id_penjual   = intval($item['id_penjual']);
        $jumlah       = intval($item['jumlah']);
        $harga_satuan = intval($item['harga']);
        $total_harga  = $harga_satuan * $jumlah;
        $status       = 'Selesai'; 

        // Query Insert ke tabel transaksi (Sesuaikan nama kolom jika ada perbedaan)
        $query = "INSERT INTO transaksi (id_produk, id_penjual, nama_pembeli, jumlah, total_harga, status) 
                  VALUES ($id_produk, $id_penjual, '$nama_pembeli_clean', $jumlah, $total_harga, '$status')";
        
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Gagal mencatat transaksi untuk produk ID $id_produk: " . mysqli_error($conn));
        }

        // Query Update Stok Produk (Menggunakan GREATEST agar stok tidak minus di bawah 0)
        $update_stok = "UPDATE produk SET stok = GREATEST(0, stok - $jumlah) WHERE id_produk = $id_produk";
        if (!mysqli_query($conn, $update_stok)) {
            throw new Exception("Gagal memperbarui stok untuk produk ID $id_produk: " . mysqli_error($conn));
        }
    }

    // Jika seluruh looping berhasil tanpa interupsi, kunci data ke DB
    mysqli_commit($conn);
    $response = ['status' => 'success'];

} catch (Exception $e) {
    // Jika ada satu saja yang gagal, batalkan seluruh rangkaian insert di atas
    if (isset($conn) && $conn instanceof mysqli) {
        mysqli_rollback($conn);
    }
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

// Bersihkan buffer dan keluarkan hanya JSON murni
ob_clean();
echo json_encode($response);
exit;