<?php
session_start();
if (!isset($_SESSION['penjual_id']) || empty($_SESSION['penjual_id'])) { 
    header("Location: login.php"); 
    exit; 
}
include '../config/database.php';

$id_penjual = intval($_SESSION['penjual_id']);

// Ambil daftar kategori dari basis data
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['tambah_produk'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = intval($_POST['id_kategori']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $foto = null;

    // Manajemen Upload File Gambar
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $foto = "prod_" . time() . "_" . uniqid() . "." . $ext;
            // Pastikan folder penyimpanan gambar ditarget dengan benar
            move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/" . $foto);
        }
    }

    $insert_query = "INSERT INTO produk (id_penjual, id_kategori, nama_produk, harga, stok, foto) 
                     VALUES ($id_penjual, $id_kategori, '$nama', $harga, $stok, '$foto')";

    if (mysqli_query($conn, $insert_query)) {
        header("Location: dashboard.php?msg=tambah_sukses");
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
    <title>Tambah Aset Baru - SellerPanel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #0d0d0f; color: #ffffff; font-family: system-ui, sans-serif; padding: 40px 24px; margin: 0; }
        .glass-card {
            background: rgba(19, 19, 22, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; padding: 32px;
            max-width: 540px; margin: 40px auto; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }
        h2 { margin: 0 0 24px 0; font-size: 22px; font-weight: 700; }
        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
        label { color: #9ca3af; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        input, select {
            padding: 12px 16px; background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;
            color: #fff; font-size: 14px; outline: none; transition: all 0.2s;
        }
        input:focus, select:focus { border-color: #a855f7; background: rgba(255, 255, 255, 0.06); }
        .btn-submit {
            background: #a855f7; color: white; border: none; padding: 14px;
            border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; margin-top: 10px;
        }
        .btn-submit:hover { background: #9333ea; }
        .btn-batal { text-align: center; color: #ef4444; text-decoration: none; font-size: 14px; font-weight: 600; display: block; margin-top: 16px; }
    </style>
</head>
<body>

    <div class="glass-card">
        <h2>Pasarkan Produk Digital Baru</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Nama Komoditas / Produk</label>
                <input type="text" name="nama_produk" placeholder="Contoh: E-Book Master Class UI/UX Design" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while($kat = mysqli_fetch_assoc($kategori_query)): ?>
                        <option value="<?php echo $kat['id_kategori']; ?>"><?php echo htmlspecialchars($kat['nama_kategori']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga Lisensi Jual (Rp)</label>
                <input type="number" name="harga" placeholder="Masukkan angka nominal saja" required>
            </div>

            <div class="form-group">
                <label>Stok Berkas / Akses</label>
                <input type="number" name="stok" placeholder="Contoh: 100" required>
            </div>

            <div class="form-group">
                <label>Gambar Cover / Preview</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>

            <button type="submit" name="tambah_produk" class="btn-submit">Unyguhkan ke Pasar Ekosistem</button>
            <a href="dashboard.php" class="btn-batal">Batalkan Penambahan</a>
        </form>
    </div>

</body>
</html>