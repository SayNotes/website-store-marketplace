// 1. Animasi Intro dengan GSAP (Mencerminkan transisi estetik gambar)
document.addEventListener("DOMContentLoaded", () => {
    gsap.from(".hero-title", { duration: 1, y: 50, opacity: 0, ease: "power4.out" });
    gsap.from(".hero-subtitle", { duration: 1, y: 30, opacity: 0, delay: 0.3, ease: "power4.out" });
    gsap.from(".product-card", { duration: 0.8, scale: 0.9, opacity: 0, stagger: 0.1, delay: 0.5 });
});

// 2. Sistem Keranjang Belanja (Menggunakan LocalStorage agar Real-time antar halaman)
function tambahKeKeranjang(idProduk) {
    let keranjang = JSON.parse(localStorage.getItem('keranjang')) || [];
    
    // Check jika produk sudah ada di keranjang
    let itemData = keranjang.find(item => item.id === idProduk);
    if (itemData) {
        itemData.jumlah += 1;
    } else {
        keranjang.push({ id: idProduk, jumlah: 1 });
    }
    
    localStorage.setItem('keranjang', JSON.stringify(keranjang));
    alert('Produk berhasil ditambahkan ke keranjang!');
}