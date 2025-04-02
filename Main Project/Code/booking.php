<?php
require 'connection.php';

// Initialize variables
$errors = [];
$room = [];
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

// Helper function to format price
function formatRupiah($number) {
    return 'Rp. ' . number_format($number, 0, ',', '.');
}

// Get room details from database
if ($room_id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $room = $result->fetch_assoc();
        
        if (!$room) {
            die("Room not found");
        }
        
    } catch (Exception $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid room selection");
}

$image_path = $_SERVER['DOCUMENT_ROOT'] . '/Code/' . $room['image_booking'];
if (file_exists($image_path)) {
    echo "<!-- File exists at: " . $image_path . " -->";
} else {
    echo "<!-- File NOT found at: " . $image_path . " -->";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boboin.aja - Review Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="booking.css">
</head>

<body>
    <div class="header">
        <img src="LOGO.png" alt="boboin.aja" class="logo">
        <div class="page-title">Review Booking</div>
    </div>

    <div class="container mx-auto p-4 max-w-4xl">
        <div class="room-card">
            <div class="flex flex-col md:flex-row gap-4 p-4">
                <div class="w-full md:w-1/3">
                    <?php
                    $base_url = "http://" . $_SERVER['HTTP_HOST'] . '/Code/'; ?>
                    <img src="<?php echo htmlspecialchars($room['image_booking']); ?>" 
                         alt="<?php echo htmlspecialchars($room['name'] ?? 'Room'); ?>" class="w-full rounded-lg">
                </div>
                <div class="w-full md:w-2/3">
                    <h1 class="text-xl font-bold"><?php echo htmlspecialchars($room['name'] ?? 'Room Name'); ?></h1>
                    <p class="text-gray-700 mt-2">⭐ <?php echo htmlspecialchars($room['rating'] ?? '0'); ?></p>
                    <p class="text-gray-700">👥 <?php echo htmlspecialchars($room['capacity'] ?? '0'); ?> peoples</p>
                    <?php if ($room['breakfast_included'] ?? false): ?>
                        <p class="text-gray-700">🍳 Breakfast included</p>
                    <?php endif; ?>
                    <p class="text-2xl font-bold text-teal-900 mt-2"><?php echo formatRupiah($room['price'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <!-- Form container - single white card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Personal Information</h2>
            <form method="POST" class="space-y-4">
                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        First Name<span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="text" name="first_name" class="input-field" required>
                </div>
                
                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" class="input-field">
                </div>
                        
                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Date of Birth<span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <div class="date-input">
                        <input type="text" name="day" class="input-field" placeholder="dd" maxlength="2" 
                            pattern="\d{2}" title="Please enter 2 digits (01-31)" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        <input type="text" name="month" class="input-field" placeholder="mm" maxlength="2"
                            pattern="\d{2}" title="Please enter 2 digits (01-12)" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        <input type="text" name="year" class="input-field" placeholder="yyyy" maxlength="4"
                            pattern="\d{4}" title="Please enter 4 digits" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: DD MM YYYY (numbers only)</p>
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email<span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="email" name="email" class="input-field" required>
                </div>
                
                <!-- Phone Number -->
                <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number<span class="text-red-500 ml-0.5">*</span>
                </label>
                
                <input type="tel" name="phone" class="input-field w-full" 
                    pattern="[0-9]{8,15}" title="Please enter 8-15 digits"
                    oninput="this.value=this.value.replace(/[^0-9+]/g,'');" required
                    placeholder="Enter phone number (e.g. +628123456789 or 08123456789)">
                
                <p class="text-xs text-gray-500 mt-1">Enter phone number (8-15 digits, may include country code)</p>
            </div>

               <!-- Add On Request -->
            <h2 class="text-xl font-bold mt-6 pt-4 border-t border-gray-200">Add On Request</h2>
            <div class="space-y-2">
                <label class="flex items-center justify-between py-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="early_checkin" id="early_checkin" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                            data-price="350000" <?php echo isset($_POST['early_checkin']) ? 'checked' : ''; ?>>
                        <span>Early Check In (11:00 WIB)</span>
                    </div>
                    <span class="text-gray-800 font-medium"><?php echo formatRupiah(350000); ?></span>
                </label>
                <label class="flex items-center justify-between py-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="late_checkout" id="late_checkout" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                            data-price="350000" <?php echo isset($_POST['late_checkout']) ? 'checked' : ''; ?>>
                        <span>Late Check Out (15:00 WIB)</span>
                    </div>
                    <span class="text-gray-800 font-medium"><?php echo formatRupiah(350000); ?></span>
                </label>
                <label class="flex items-center justify-between py-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="extra_bed" id="extra_bed" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                            data-price="150000" <?php echo isset($_POST['extra_bed']) ? 'checked' : ''; ?>>
                        <span>Extra Bed</span>
                    </div>
                    <span class="text-gray-800 font-medium"><?php echo formatRupiah(150000); ?></span>
                </label>
            </div>

                <!-- Payment Details -->
                <h2 class="text-xl font-bold mb-2">Payment Details</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span><?php echo htmlspecialchars($room['name']); ?> (<span id="nights-count"><?php echo $nights ?? 1; ?></span> nights)</span>
                        <span id="room-price"><?php echo formatRupiah($room['price']); ?></span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Request add-on</span>
                        <span id="addons-total"><?php echo formatRupiah($addons ?? 0); ?></span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Tax (10%)</span>
                        <span id="tax-amount"><?php echo formatRupiah(($room['price'] + ($addons ?? 0)) * 0.1); ?></span>
                    </div>
                    <div class="flex justify-between font-bold py-1 border-t border-gray-300 mt-1">
                        <span>Total</span>
                        <span id="total-price"><?php echo formatRupiah($total_price ?? ($room['price'] * 1.1)); ?></span>
                    </div>
                </div>
                <button type="submit" class="qris-button mt-6 w-full py-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/1200px-QR_code_for_mobile_English_Wikipedia.svg.png" alt="QR Code">
                Payment with QRIS
            </button>
            </form>
        </div>

    <script>
            // Fungsi untuk menghitung total harga
            function calculateTotal() {
                const roomPrice = <?php echo $room['price']; ?>;
                const nights = parseInt(document.getElementById('nights-count').textContent) || 1;
                const basePrice = roomPrice * nights;
                
                let addonsTotal = 0;
                document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
                    addonsTotal += parseInt(checkbox.dataset.price);
                });
                
                const subtotal = basePrice + addonsTotal;
                const tax = subtotal * 0.1;
                const total = subtotal + tax;
                
                // Update tampilan
                document.getElementById('addons-total').textContent = 'Rp ' + addonsTotal.toLocaleString('id-ID');
                document.getElementById('tax-amount').textContent = 'Rp ' + Math.round(tax).toLocaleString('id-ID');
                document.getElementById('total-price').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
                
                // Update nilai untuk form submission
                document.getElementById('room-price-value').value = basePrice;
                document.getElementById('addons-total-value').value = addonsTotal;
                document.getElementById('total-price-value').value = total;
            }
            
            // Event listener untuk checkbox add-on
            document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', calculateTotal);
            });
            
            // Hitung total saat halaman pertama kali dimuat
            document.addEventListener('DOMContentLoaded', calculateTotal);
        </script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date validation
            const dayInput = document.querySelector('input[name="day"]');
            const monthInput = document.querySelector('input[name="month"]');
            const yearInput = document.querySelector('input[name="year"]');
            
            [dayInput, monthInput, yearInput].forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value && !/^\d+$/.test(this.value)) {
                        this.setCustomValidity('Numbers only please');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });

            // Phone number validation
            const phoneInput = document.querySelector('input[name="phone"]');
            phoneInput.addEventListener('blur', function() {
                if (this.value && !/^\d{9,13}$/.test(this.value)) {
                    this.setCustomValidity('Please enter 9-13 digits');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('input[name="phone"]');
            
            // Allow numbers and + sign only
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9+]/g,'');
            });
            
            // Basic validation
            phoneInput.addEventListener('blur', function() {
                const phone = this.value.replace(/[^0-9]/g,''); // Count only digits
                if (phone.length < 8 || phone.length > 15) {
                    this.setCustomValidity('Please enter 8-15 digits');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
    </body>
    </html>