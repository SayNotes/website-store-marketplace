<?php
session_start();
if (!isset($_SESSION['penjual_id'])) { header("Location: login.php"); exit; }
include '../config/database.php';

$id_penjual = $_SESSION['penjual_id'];

// Mengamankan penangkapan ID baik via parameter 'id' (dari dashboard) maupun 'edit'
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['edit']) ? intval($_GET['edit']) : 0);

// Ambil data produk yang mau diedit
$res = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk=$id_produk AND id_penjual=$id_penjual");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['update_produk'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    
    // Jika ganti gambar baru
    if ($_FILES['foto']['name'] != "") {
        $foto = time() . "_" . $_FILES['foto']['name']; // Menambahkan timestamp agar nama file unik
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/".$foto);
        mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga=$harga, stok=$stok, foto='$foto' WHERE id_produk=$id_produk");
    } else {
        // Jika pakai gambar lama
        mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga=$harga, stok=$stok WHERE id_produk=$id_produk");
    }
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .edit-box { max-width:500px; margin:50px auto; background:#fff; padding:30px; border-radius:16px; border:1px solid var(--border-color); }
        input { width:100%; padding:10px; margin-top:5px; margin-bottom:15px; border-radius:6px; border:1px solid #ccc; }
        .img-preview { width: 120px; height: 120px; border-radius: 10px; object-fit: cover; border: 1px solid #ddd; margin-top: 5px; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>
    <div class="grid-bg"></div>
    <div class="edit-box">
        <h3>Edit Data Produk</h3><br>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?php echo $data['harga']; ?>" required>

            <label>Stok</label>
            <input type="number" name="stok" value="<?php echo $data['stok']; ?>" required>

            <label>Gambar Produk Saat Ini</label>
            <?php 
                $gambar_path = "../assets/img/" . $data['foto'];
                $foto_sekarang = (!empty($data['foto']) && file_exists($gambar_path)) ? $gambar_path : "../assets/img/default-product.png";
            ?>
            <img src="<?php echo $foto_sekarang; ?>" alt="Preview" class="img-preview">

            <label>Ubah Gambar (Biarkan kosong jika tidak diganti)</label>
            <input type="file" name="foto" accept="image/*">

            <button type="submit" name="update_produk" class="btn-checkout" style="width:100%; border:none; padding:12px; background:#a855f7; color:white; border-radius:8px; font-weight:600; cursor:pointer;">Simpan Perubahan</button>
            <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px; color:var(--text-muted); font-size:14px; text-decoration:none;">Batal</a>
        </form>
    </div>
</body>
</html>