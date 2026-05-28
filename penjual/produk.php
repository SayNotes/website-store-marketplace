<?php
session_start();
if (!isset($_SESSION['penjual_id']) || empty($_SESSION['penjual_id'])) { 
    header("Location: login.php"); 
    exit; 
}
include '../config/database.php';

$id_penjual = intval($_SESSION['penjual_id']);
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data produk spesifik milik penjual yang sedang login (Security Check)
$res = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk=$id_produk AND id_penjual=$id_penjual");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    header("Location: dashboard.php");
    exit;
}

// Ambil semua daftar kategori untuk kebutuhan dropdown form select
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['update_produk'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = intval($_POST['id_kategori']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $foto = $data['foto']; // Default pakai gambar lama

    // Logika jika seller mengganti gambar produk
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            // Hapus gambar lama di server agar penyimpanan efisien
            if (!empty($data['foto']) && file_exists("../assets/img/" . $data['foto'])) {
                unlink("../assets/img/" . $data['foto']);
            }
            // Generate nama berkas acak yang aman
            $foto = "prod_" . time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/" . $foto);
        }
    }

    $update_query = "UPDATE produk SET 
                     nama_produk='$nama', 
                     id_kategori=$id_kategori, 
                     harga=$harga, 
                     stok=$stok, 
                     foto='$foto' 
                     WHERE id_produk=$id_produk AND id_penjual=$id_penjual";

    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboard.php?msg=edit_sukses");
    } else {
        header("Location: dashboard.php?msg=gagal");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Aset Digital - SellerPanel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/img/coin.png">
    <style>
        body {
            background: #0d0d0f;
            color: #ffffff;
            font-family: system-ui, sans-serif;
            padding: 40px 24px;
            margin: 0;
        }
        .glass-card {
            background: rgba(19, 19, 22, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 32px;
            max-width: 540px;
            margin: 40px auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }
        h2 { margin: 0 0 24px 0; font-size: 22px; font-weight: 700; }
        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
        label { color: #9ca3af; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        input, select {
            padding: 12px 16px; background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;
            color: #fff; font-size: 14px; outline: none; transition: all 0.2s;
        }
        input:focus, select:focus { border-color: #3b82f6; background: rgba(255, 255, 255, 0.06); }
        .img-preview { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); margin-top: 4px; }
        .btn-submit {
            background: #3b82f6; color: white; border: none; padding: 14px;
            border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; margin-top: 10px;
        }
        .btn-submit:hover { background: #2563eb; }
        .btn-batal { text-align: center; color: #9ca3af; text-decoration: none; font-size: 14px; font-weight: 500; display: block; margin-top: 16px; }
        .btn-batal:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="glass-card">
        <h2>Edit Detail Produk Digital</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori Produk</label>
                <select name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while($kat = mysqli_fetch_assoc($kategori_query)): ?>
                        <option value="<?php echo $kat['id_kategori']; ?>" <?php echo ($kat['id_kategori'] == $data['id_kategori']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga Jual (Rp)</label>
                <input type="number" name="harga" value="<?php echo $data['harga']; ?>" required>
            </div>

            <div class="form-group">
                <label>Stok Komoditas</label>
                <input type="number" name="stok" value="<?php echo $data['stok']; ?>" required>
            </div>

            <div class="form-group">
                <label>Preview Gambar Saat Ini</label>
                <?php 
                    $gambar_path = "../assets/img/" . $data['foto'];
                    $foto_sekarang = (!empty($data['foto']) && file_exists($gambar_path)) ? $gambar_path : "../assets/img/default-product.png";
                ?>
                <img src="<?php echo $foto_sekarang; ?>" class="img-preview" alt="Current Preview">
            </div>

            <div class="form-group">
                <label>Unggah Gambar Baru (Kosongkan jika tidak diganti)</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <button type="submit" name="update_produk" class="btn-submit">Simpan Perubahan Aset</button>
            <a href="dashboard.php" class="btn-batal">Kembali ke Dashboard</a>
        </form>
    </div>

</body>
</html>