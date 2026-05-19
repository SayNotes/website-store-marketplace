/**
 * Inspired Market - Client-Side Cart Processor
 */

function tambahKeKeranjangDenganQty(id, nama, harga, kuantitas) {
    // Tarik list item keranjang lama dari browser storage
    let keranjang = JSON.parse(localStorage.getItem('mp_purple_cart')) || [];
    
    // Cek keberadaan produk di list
    let produkAda = keranjang.find(item => item.id === id);

    if (produkAda) {
        // Tambahkan jumlahnya sesuai input counter user
        produkAda.jumlah += kuantitas;
    } else {
        // Daftarkan sebagai produk baru di dalam list belanja
        keranjang.push({
            id: id,
            nama: nama,
            harga: harga,
            jumlah: kuantitas
        });
    }

    // Tulis kembali datanya ke LocalStorage
    localStorage.setItem('mp_purple_cart', JSON.stringify(keranjang));
    
    // Notifikasi popup mini sukses
    alert(`⚡ Sukses! ${kuantitas} pcs "${nama}" masuk ke keranjang.`);
}