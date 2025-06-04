document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen input
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const personSelect = document.getElementById('person');

    // Fungsi untuk format tanggal ke YYYY-MM-DD
    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };

    // Fungsi untuk menyimpan data ke localStorage
    function saveBookingData() {
        if (checkinInput && checkinInput.value) {
            localStorage.setItem('checkinDate', checkinInput.value);
        }
        if (checkoutInput && checkoutInput.value) {
            localStorage.setItem('checkoutDate', checkoutInput.value);
        }
        if (personSelect && personSelect.value) {
            localStorage.setItem('personCount', personSelect.value);
        }
        console.log("Saved booking data:", {
            checkinDate: checkinInput?.value,
            checkoutDate: checkoutInput?.value,
            personCount: personSelect?.value
        });
    }

    // Fungsi untuk memuat data dari localStorage
    function loadBookingData() {
        const savedCheckin = localStorage.getItem('checkinDate');
        const savedCheckout = localStorage.getItem('checkoutDate');
        const savedPerson = localStorage.getItem('personCount');

        console.log("Loaded booking data:", {
            savedCheckin,
            savedCheckout,
            savedPerson
        });

        // Set default dates jika belum ada data tersimpan
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        // Load atau set default untuk check-in
        if (checkinInput) {
            if (savedCheckin) {
                checkinInput.value = savedCheckin;
            } else {
                checkinInput.value = formatDate(today);
            }
            checkinInput.min = formatDate(today);
        }

        // Load atau set default untuk check-out
        if (checkoutInput) {
            if (savedCheckout) {
                checkoutInput.value = savedCheckout;
            } else {
                checkoutInput.value = formatDate(tomorrow);
            }
            
            // Set minimum checkout berdasarkan checkin yang ada
            if (checkinInput && checkinInput.value) {
                const checkinDate = new Date(checkinInput.value);
                const minCheckout = new Date(checkinDate);
                minCheckout.setDate(checkinDate.getDate() + 1);
                checkoutInput.min = formatDate(minCheckout);
            } else {
                checkoutInput.min = formatDate(tomorrow);
            }
        }

        // Load person count
        if (personSelect && savedPerson) {
            personSelect.value = savedPerson;
        }
    }

    // Setup event listeners untuk save data ketika input berubah
    function setupEventListeners() {
        if (checkinInput) {
            checkinInput.addEventListener('change', function() {
                // Update minimum checkout date
                const selectedCheckin = new Date(this.value);
                const newMinCheckout = new Date(selectedCheckin);
                newMinCheckout.setDate(selectedCheckin.getDate() + 1);
                
                if (checkoutInput) {
                    checkoutInput.min = formatDate(newMinCheckout);
                    
                    // Jika checkout date kurang dari minimum, update checkout
                    if (new Date(checkoutInput.value) <= selectedCheckin) {
                        checkoutInput.value = formatDate(newMinCheckout);
                    }
                }
                
                saveBookingData();
            });
        }

        if (checkoutInput) {
            checkoutInput.addEventListener('change', saveBookingData);
        }

        if (personSelect) {
            personSelect.addEventListener('change', saveBookingData);
        }
    }

    // Inisialisasi
    loadBookingData();
    setupEventListeners();

    // Function untuk clear booking data (jika diperlukan)
    window.clearBookingData = function() {
        localStorage.removeItem('checkinDate');
        localStorage.removeItem('checkoutDate');
        localStorage.removeItem('personCount');
        console.log("Booking data cleared");
    };

    // Function untuk sync data ke form lain (jika diperlukan)
    window.syncBookingData = function() {
        loadBookingData();
    };
});