<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .pay-option {
            display: flex; align-items: center; gap: 12px;
            background: white; border: 1px solid var(--border-color);
            padding: 16px; border-radius: 12px; margin-bottom: 12px; cursor: pointer;
        }
        .pay-option input { scale: 1.2; }
    </style>
</head>
<body>
    <div class="grid-bg"></div>
    <div class="cart-container" style="max-width:600px; margin:0 auto;">
        <h2>Metode Pembayaran</h2><br>
        
        <label class="pay-option">
            <input type="radio" name="metode" value="Dana" checked>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon" style="color:#22c55e">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
            </svg>
            <span>E-Wallet (DANA / OVO / GoPay)</span>
        </label>

        <label class="pay-option">
            <input type="radio" name="metode" value="Transfer Bank">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon" style="color:#3b82f6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-3.75h16.5M2.25 13.5h19.5M6.25 6.75h11.5M4.5 21V6.75A2.25 2.25 0 0 1 6.75 4.5h10.5a2.25 2.25 0 0 1 2.25 2.25V21M7.5 18.75v-5.25m4.125 5.25v-5.25m3.75 5.25v-5.25" />
            </svg>
            <span>Transfer Bank Virtual Account</span>
        </label>

        <button onclick="prosesCheckout()" class="btn-checkout" style="width:100%; text-align:center; border:none; cursor:pointer;">Konfirmasi & Bayar Sekarang</button>
    </div>

    <script>
        function prosesCheckout() {
            let konfirmasi = confirm("Apakah Anda yakin ingin melanjutkan pembayaran dengan metode ini?");
            if (konfirmasi) {
                alert("Pembayaran sukses dikonfirmasi! Terima kasih telah berbelanja.");
                localStorage.removeItem('keranjang'); // Kosongkan keranjang belanja
                window.location.href = 'index.php';
            }
        }
    </script>
</body>
</html>