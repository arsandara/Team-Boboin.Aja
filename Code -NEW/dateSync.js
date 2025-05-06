// dateSync.js
document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen input
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const personSelect = document.getElementById('person');

    // Fungsi untuk menyimpan data ke localStorage
    function saveBookingData() {
        localStorage.setItem('checkinDate', checkinInput.value);
        localStorage.setItem('checkoutDate', checkoutInput.value);
        localStorage.setItem('personCount', personSelect.value);
    }

    // Fungsi untuk memuat data dari localStorage
    function loadBookingData() {
        const savedCheckin = localStorage.getItem('checkinDate');
        const savedCheckout = localStorage.getItem('checkoutDate');
        const savedPerson = localStorage.getItem('personCount');

        if (savedCheckin && checkinInput) {
            checkinInput.value = savedCheckin;
        }
        if (savedCheckout && checkoutInput) {
            checkoutInput.value = savedCheckout;
        }
        if (savedPerson && personSelect) {
            personSelect.value = savedPerson;
        }
    }

    // Jika elemen ada di halaman ini, tambahkan event listener
    if (checkinInput && checkoutInput && personSelect) {
        // Load data saat halaman dimuat
        loadBookingData();

        // Simpan data saat diubah
        checkinInput.addEventListener('change', saveBookingData);
        checkoutInput.addEventListener('change', saveBookingData);
        personSelect.addEventListener('change', saveBookingData);
    } else {
        // Jika elemen tidak ada (halaman lain), tetap load data
        loadBookingData();
    }
});