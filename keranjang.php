<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/img/coin.png">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>

    <div class="grid-overlay"></div>
    <div class="purple-bokeh"></div>

    <header>
        <div class="container navbar-container">
            <div class="logo" onclick="window.location.href='index.php'">
                <span class="white">Hamba</span><span class="purple">Market</span>
            </div>
            <div class="nav-actions">
                <a href="index.php" class="nav-link">← Kembali Belanja</a>
            </div>
        </div>
    </header>

    <main class="container cart-page-main">
        <h2 class="section-title">Keranjang Belanja Anda</h2>

        <div class="cart-layout">
            <div class="cart-items-wrapper" id="cart-container"></div>

            <div class="cart-summary">
                <h3>Ringkasan Belanja</h3>
                <div class="cart-summary-row">
                    <span>Total Item</span>
                    <span id="total-qty">0 pcs</span>
                </div>
                <div class="cart-summary-total">
                    <span>Total Harga</span>
                    <span class="purple" id="total-price">Rp 0</span>
                </div>
                <button class="btn-add-to-cart" onclick="prosesCheckout()">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </main>

    <script>
        // Fungsi helper untuk mengubah angka menjadi format mata uang Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk mengambil data dari localStorage dan merendernya ke HTML
        function renderKeranjang() {
            const cartContainer = document.getElementById('cart-container');
            const totalQtyEl    = document.getElementById('total-qty');
            const totalPriceEl  = document.getElementById('total-price');

            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];

            if (keranjang.length === 0) {
                cartContainer.innerHTML = `
                    <div class="cart-empty">
                        <p>Keranjang belanja Anda masih kosong.</p>
                        <a href="index.php" class="btn-add-to-cart" style="width:auto; padding: 12px 28px;">Cari Produk</a>
                    </div>`;
                totalQtyEl.innerText  = '0 pcs';
                totalPriceEl.innerText = 'Rp 0';
                return;
            }

            let html = '';
            let totalHarga = 0;
            let totalQty   = 0;

            keranjang.forEach((item, index) => {
                const subtotal = item.harga * item.jumlah;
                totalHarga += subtotal;
                totalQty   += item.jumlah;

                html += `
                    <div class="resource-card">
                        <div class="cart-item-info">
                            <h3>${item.nama}</h3>
                            <span class="price">
                                <img src="assets/img/coin.png" alt="coin" class="money-icon">
                                ${formatRupiah(item.harga)}
                            </span>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-counter">
                                <button class="count-btn" onclick="updateJumlah(${index}, -1)">−</button>
                                <input type="number" class="qty-input" value="${item.jumlah}" readonly>
                                <button class="count-btn" onclick="updateJumlah(${index}, 1)">+</button>
                            </div>
                            <span class="cart-item-subtotal">${formatRupiah(subtotal)}</span>
                            <button class="btn-hapus" onclick="hapusItem(${index})">Hapus</button>
                        </div>
                    </div>`;
            });

            cartContainer.innerHTML = html;
            totalQtyEl.innerText   = totalQty + ' pcs';
            totalPriceEl.innerText = formatRupiah(totalHarga);
        }

        // Fungsi mengubah jumlah barang (+ atau -)
        function updateJumlah(index, arah) {
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            keranjang[index].jumlah = Math.max(1, keranjang[index].jumlah + arah);
            localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
            renderKeranjang();
        }

        // Fungsi menghapus item dari keranjang
        function hapusItem(index) {
            if(confirm('Hapus item ini dari keranjang?')) {
                let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
                keranjang.splice(index, 1);
                localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
                renderKeranjang();
            }
        }

        // FUNGSI UTAMA: Mengirimkan array objek dari localStorage ke PHP Backend via Fetch API
        function prosesCheckout() {
            let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
            
            if (keranjang.length === 0) {
                alert('❌ Keranjang belanja Anda masih kosong!');
                return;
            }

            // Kirim data ke file aksi checkout menggunakan metode POST JSON
            fetch('checkout_aksi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(keranjang)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('🎉 Pembayaran Berhasil! Aset digital Anda sukses dicatat.');
                    localStorage.removeItem('mp_purple_cart'); // Bersihkan isi keranjang belanja di browser
                    window.location.href = 'index.php'; // Alihkan kembali ke halaman utama
                } else {
                    alert('❌ Gagal memproses transaksi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('⚠️ Terjadi gangguan koneksi atau kegagalan sistem pada server.');
            });
        }

        // Jalankan render saat halaman pertama kali dimuat
        document.addEventListener("DOMContentLoaded", () => {
            renderKeranjang();
            if (window.gsap) {
                gsap.from(".cart-layout", { duration: 0.6, y: 20, opacity: 0, ease: "power2.out" });
            }
        });
    </script>
</body>
</html>