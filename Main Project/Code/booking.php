<?php
require 'connection.php';

// Initialize variables
$errors = [];
$room = [];
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

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

// Process booking form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $persons = intval($_POST['persons'] ?? 1);
    $early_checkin = isset($_POST['early_checkin']) ? 1 : 0;
    $late_checkout = isset($_POST['late_checkout']) ? 1 : 0;
    $extra_bed = isset($_POST['extra_bed']) ? 1 : 0;

    // Validate inputs
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($check_in)) $errors[] = "Check-in date is required";
    if (empty($check_out)) $errors[] = "Check-out date is required";
    if ($check_in >= $check_out) $errors[] = "Check-out date must be after check-in date";

    // Calculate total price
    $base_price = $room['price'];
    $addons = 0;
    if ($early_checkin) $addons += 350000;
    if ($late_checkout) $addons += 350000;
    if ($extra_bed) $addons += 150000;
    $tax = ($base_price + $addons) * 0.1;
    $total_price = $base_price + $addons + $tax;

    // Calculate number of nights
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $nights = $check_out_date->diff($check_in_date)->days;

    // Save to database if no errors
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO bookings 
                                  (room_id, first_name, last_name, date_of_birth, email, phone, check_in, check_out, persons, 
                                  early_checkin, late_checkout, extra_bed, total_price, nights) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("isssssssiiiiii", 
                $room_id,
                $first_name,
                $last_name,
                $date_of_birth,
                $email,
                $phone,
                $check_in,
                $check_out,
                $persons,
                $early_checkin,
                $late_checkout,
                $extra_bed,
                $total_price,
                $nights
            );
            
            $stmt->execute();
            
            header("Location: booking_success.php?id=".$conn->insert_id);
            exit;
            
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Helper function to format price
function formatRupiah($number) {
    return 'Rp. ' . number_format($number, 0, ',', '.');
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
    <!-- Header tetap dipertahankan -->
    <div class="header">
        <img src="LOGO.png" alt="boboin.aja" class="logo">
        <div class="page-title">Review Booking</div>
        <div style="width: 100px;"></div>
    </div>

    <div class="container mx-auto p-4 max-w-4xl">
        <!-- Card informasi booking -->
        <div class="booking-details">
            <div class="booking-detail-item">
                <h4>Check In</h4>
                <p><?php echo htmlspecialchars($_POST['check_in'] ?? ''); ?> (14.00 WIB)</p>
            </div>
            <div class="booking-detail-item">
                <h4>Check Out</h4>
                <p><?php echo htmlspecialchars($_POST['check_out'] ?? ''); ?> (12.00 WIB)</p>
            </div>
            <div class="booking-detail-item">
                <h4>Person</h4>
                <p><?php echo intval($_POST['persons'] ?? 1); ?> Person</p>
            </div>
        </div>

        <!-- Card informasi kamar -->
        <div class="room-card">
            <div class="flex flex-col md:flex-row gap-4 p-4">
                <div class="w-full md:w-1/3">
                    <img src="<?php echo htmlspecialchars($room['image'] ?? 'default-room.jpg'); ?>" alt="<?php echo htmlspecialchars($room['name'] ?? 'Room'); ?>" class="w-full rounded-lg">
                </div>
                <div class="w-full md:w-2/3">
                    <h1 class="text-xl font-bold"><?php echo htmlspecialchars($room['name'] ?? 'Room Name'); ?></h1>
                    <p class="text-gray-700 mt-2">⭐ <?php echo htmlspecialchars($room['rating'] ?? '0'); ?> </p>
                    <p class="text-gray-700">👥 <?php echo htmlspecialchars($room['capacity'] ?? '0'); ?> peoples</p>
                    <?php if ($room['breakfast_included'] ?? false): ?>
                        <p class="text-gray-700">🍳 Breakfast included</p>
                    <?php endif; ?>
                    <p class="text-2xl font-bold text-teal-900 mt-2">Rp. <?php echo number_format($room['price'] ?? 0, 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <!-- Form personal information -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Personal Information</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <input type="text" name="first_name" class="input-field" placeholder="First Name*" required>
                </div>
                <div>
                    <input type="text" name="last_name" class="input-field" placeholder="Last Name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth*</label>
                    <div class="date-input">
                        <input type="text" name="day" class="input-field" placeholder="dd" maxlength="2" required>
                        <input type="text" name="month" class="input-field" placeholder="mm" maxlength="2" required>
                        <input type="text" name="year" class="input-field" placeholder="yyyy" maxlength="4" required>
                    </div>
                </div>
                <div>
                    <input type="email" name="email" class="input-field" placeholder="Email*" required>
                </div>
                <div>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">+62</span>
                        <input type="tel" name="phone" class="input-field rounded-l-none" placeholder="Phone Number*" required>
                    </div>
                </div>

                <!-- Add On Request dengan jarak yang lebih rapat -->
                <h2 class="text-xl font-bold mt-4 compact-section">Add On Request</h2>
                <div class="space-y-2 compact-space"> <!-- Mengurangi space-y dari 3 ke 2 -->
                    <label class="flex items-center justify-between py-1 compact-space"> <!-- Mengurangi padding-y -->
                        <div class="flex items-center">
                            <input type="checkbox" name="early_checkin" id="early_checkin" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                                   data-price="350000" <?php echo isset($_POST['early_checkin']) ? 'checked' : ''; ?>>
                            <span class="ml-2">Early Check In (11:00 WIB)</span>
                        </div>
                        <span class="text-teal-900 font-medium"><?php echo formatRupiah(350000); ?></span>
                    </label>
                    <label class="flex items-center justify-between py-1 compact-space">
                        <div class="flex items-center">
                            <input type="checkbox" name="late_checkout" id="late_checkout" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                                   data-price="350000" <?php echo isset($_POST['late_checkout']) ? 'checked' : ''; ?>>
                            <span class="ml-2">Late Check Out (15:00 WIB)</span>
                        </div>
                        <span class="text-teal-900 font-medium"><?php echo formatRupiah(350000); ?></span>
                    </label>
                    <label class="flex items-center justify-between py-1 compact-space">
                        <div class="flex items-center">
                            <input type="checkbox" name="extra_bed" id="extra_bed" class="h-5 w-5 text-teal-600 rounded addon-checkbox" 
                                   data-price="150000" <?php echo isset($_POST['extra_bed']) ? 'checked' : ''; ?>>
                            <span class="ml-2">Extra Bed</span>
                        </div>
                        <span class="text-teal-900 font-medium"><?php echo formatRupiah(150000); ?></span>
                    </label>
                </div>

                <!-- Payment Details dengan jarak yang lebih rapat -->
                <h2 class="text-xl font-bold mt-4 compact-section">Payment Details</h2>
                <div class="bg-gray-50 p-3 rounded-lg compact-space"> <!-- Mengurangi padding -->
                    <div class="flex justify-between py-1 compact-space">
                        <span><?php echo htmlspecialchars($room['name']); ?> (<span id="nights-count"><?php echo $nights ?? 1; ?></span> nights)</span>
                        <span id="room-price"><?php echo formatRupiah($room['price']); ?></span>
                    </div>
                    <div class="flex justify-between py-1 compact-space">
                        <span>Request add-on</span>
                        <span id="addons-total"><?php echo formatRupiah($addons ?? 0); ?></span>
                    </div>
                    <div class="flex justify-between py-1 compact-space">
                        <span>Tax (10%)</span>
                        <span id="tax-amount"><?php echo formatRupiah(($room['price'] + ($addons ?? 0)) * 0.1); ?></span>
                    </div>
                    <div class="flex justify-between font-bold py-1 border-t border-gray-300 mt-1 compact-space">
                        <span>Total</span>
                        <span id="total-price"><?php echo formatRupiah($total_price ?? ($room['price'] * 1.1)); ?></span>
                    </div>
                </div>

                <button type="submit" class="mt-4 w-full bg-teal-900 text-white py-3 rounded-lg font-bold hover:bg-teal-800 transition duration-200">Complete Booking</button>
            </form>
        </div>
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
    </body>
    </html>