document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.querySelector('.div-hero-section');
    
    if (wrapper) {
        // Ambil semua gambar (kartu)
        const icons = Array.from(wrapper.querySelectorAll('img'));
        let currentIndex = 0;

        if (icons.length > 1) {
            function rotateIcons() {
                const currentIcon = icons[currentIndex];
                
                // Tentukan siapa kartu yang bakal tampil selanjutnya
                const nextIndex = (currentIndex + 1) % icons.length;
                const nextIcon = icons[nextIndex];

                // 1. Lempar kartu yang sekarang ke KIRI (Tambahkan class leaving)
                currentIcon.classList.remove('active');
                currentIcon.classList.add('leaving');

                // 2. Tarik kartu selanjutnya dari KANAN ke TENGAH (Tambahkan class active)
                nextIcon.classList.remove('leaving'); // Pastikan dia bersih
                nextIcon.classList.add('active');

                // 3. Bersihkan class 'leaving' setelah animasi selesai (700ms)
                // Biar kartunya diam-diam balik ke posisi standby di kanan
                setTimeout(() => {
                    currentIcon.classList.remove('leaving');
                }, 700);

                // Update index
                currentIndex = nextIndex;
            }

            // Jalankan animasi setiap 3 detik
            setInterval(rotateIcons, 3000);
        }
    }
});