<?php
session_start();
include '../config/database.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ambil data admin
    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Mendukung password_verify maupun plain-text untuk kemudahan development
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['admin'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - Inspired Market</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* Mengatur agar layout form berada tepat di tengah layar */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            z-index: 10;
        }

        /* Desain Glassmorphic Box (Menyesuaikan gambar panduan awal) */
        .login-box { 
            background: rgba(19, 19, 22, 0.75); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08); 
            padding: 40px; 
            border-radius: 24px; 
            width: 100%; 
            max-width: 420px; 
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); 
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h2 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 13.5px;
            color: var(--text-muted);
        }

        .form-group { 
            margin-bottom: 22px; 
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label { 
            display: block; 
            font-size: 13px; 
            font-weight: 600; 
            color: var(--text-muted);
            letter-spacing: 0.3px;
        }

        /* Modifikasi Input Field agar bernuansa Neon Dark */
        .form-group input { 
            width: 100%; 
            padding: 14px 16px; 
            border-radius: 12px; 
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color); 
            color: #ffffff;
            font-size: 14px;
            outline: none; 
            transition: all 0.25s ease;
        }

        .form-group input:focus { 
            border-color: var(--purple-primary); 
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15);
        }

        /* Notifikasi error bawaan yang dibuat menyatu */
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 24px;
            text-align: center;
            font-weight: 500;
        }

        /* Tombol Masuk/Login Neon Purple */
        .btn-submit {
            width: 100%;
            background: var(--purple-primary);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            background: #9333ea;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
        }

        .back-link a {
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="grid-overlay"></div>
    <div class="purple-bokeh" style="top: 15%; left: 15%;"></div>

    <div class="login-wrapper">
        <div class="login-box">
            
            <div class="login-header">
                <div class="logo" style="font-size: 24px; margin-bottom: 6px; pointer-events: none;">
                    <span class="white">Market</span><span class="purple">Inspired</span>
                </div>
                <h2>Login Portal</h2>
                <p>Silakan masuk untuk mengelola sistem marketplace</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert-error">
                    🔒 Username atau password salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" name="login" class="btn-submit">Masuk Sekarang</button>
            </form>

            <div class="back-link">
                <a href="../index.php">← Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if(window.gsap) {
                gsap.from(".login-box", { duration: 0.7, y: 30, opacity: 0, ease: "power3.out" });
            }
        });
    </script>
</body>
</html>