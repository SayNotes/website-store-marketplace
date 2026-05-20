<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Inspired Market</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>

    <div class="grid-overlay"></div>
    <div class="purple-bokeh"></div>

    <header>
        
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Market</span><span class="purple">Inspired</span>
            </div>
            <div class="nav-actions">
                <a href="index.php" class="nav-link">← Kembali Belanja</a>
            </div>
        </div>
    </header>

    <main class="container" style="padding-top: 40px; padding-bottom: 80px;">
        <h2 class="section-title">Keranjang Belanja Anda</h2>

        <div class="cart-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <div class="cart-items-wrapper" id="cart-container">
                </div>

            <div class="cart-summary" style="background: #131316; border: 1px solid var(--border-color); padding: 24px; border-radius: 18px; height: max-content;">
                <h3 style="font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Ringkasan Belanja</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 14px; color: var(--text-muted); font-size: 14px;">
                    <span>Total Item</span>
                    <span id="total-qty">0 pcs</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 24px; font-size: 16px; font-weight: 700;">
                    <span>Total Harga</span>
                    <span class="purple" id="total-price">Rp 0</span>
                </div>
                <button class="btn-add-to-cart" style="width: 100%; padding: 14px;" onclick="prosesCheckout()">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </main>

    <script>
        // Fungsi untuk memformat angka ke Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi me-render isi keranjang belanja
        function renderKeranjang() {
            const cartContainer = document.getElementById('cart-container');
            const totalQtyEl = document.getElementById('total-qty');
            const totalPriceEl = document.getElementById('total-price');
            
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];

            if (keranjang.length === 0) {
                cartContainer.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; background: #131316; border: 1px solid var(--border-color); border-radius: 18px;">
                        <p style="color: var(--text-muted); margin-bottom: 20px;">Keranjang belanja Anda masih kosong.</p>
                        <a href="index.php" class="btn-add-to-cart" style="text-decoration: none; display: inline-block;">Cari Produk</a>
                    </div>
                `;
                totalQtyEl.innerText = '0 pcs';
                totalPriceEl.innerText = 'Rp 0';
                return;
            }

            let html = '';
            let totalHarga = 0;
            let totalQty = 0;

            keranjang.forEach((item, index) => {
                const subtotal = item.harga * item.jumlah;
                totalHarga += subtotal;
                totalQty += item.jumlah;

                html += `
                    <div class="resource-card" style="flex-direction: row; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 20px;">
                        <div style="max-width: 50%;">
                            <div class="floating-price">
                                <img src="assets/img/coin.png" alt="coin" class="money-icon">
                            </div>
                            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">${item.nama}</h3>
                            <span class="price" style="font-size: 14px; color: var(--text-muted);">${formatRupiah(item.harga)}</span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div class="quantity-counter">
                                <button class="count-btn" onclick="updateJumlah(${index}, -1)">−</button>
                                <input type="number" class="qty-input" value="${item.jumlah}" readonly>
                                <button class="count-btn" onclick="updateJumlah(${index}, 1)">+</button>
                            </div>
                            
                            <span style="font-size: 15px; font-weight: 700; min-width: 100px; text-align: right;">${formatRupiah(subtotal)}</span>
                            
                            <button onclick="hapusItem(${index})" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 600; padding: 4px 8px;">
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
            });

            cartContainer.innerHTML = html;
            totalQtyEl.innerText = totalQty + ' pcs';
            totalPriceEl.innerText = formatRupiah(totalHarga);
        }

        function updateJumlah(index, arah) {
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            keranjang[index].jumlah += arah;

            if (keranjang[index].jumlah < 1) keranjang[index].jumlah = 1;
            
            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            renderKeranjang();
        }

        function hapusItem(index) {
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            keranjang.splice(index, 1);
            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            renderKeranjang();
        }

        function prosesCheckout() {
            alert('🚀 Fitur checkout terintegrasi! Data keranjang siap dikirim ke backend.');
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderKeranjang();
            if(window.gsap) {
                gsap.from(".cart-layout", { duration: 0.6, y: 20, opacity: 0, ease: "power2.out" });
            }
        });
    </script>
</body>
</html>