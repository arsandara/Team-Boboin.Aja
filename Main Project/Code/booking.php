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
    $guest_name = trim($_POST['guest_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $persons = intval($_POST['persons'] ?? 1);
    $special_requests = trim($_POST['special_requests'] ?? '');
    $early_checkin = isset($_POST['early_checkin']) ? 1 : 0;
    $late_checkout = isset($_POST['late_checkout']) ? 1 : 0;
    $extra_bed = isset($_POST['extra_bed']) ? 1 : 0;

    // Validate inputs
    if (empty($guest_name)) $errors[] = "Full name is required";
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
    if ($extra_bed) $addons += 100000;
    $tax = ($base_price + $addons) * 0.1;
    $total_price = $base_price + $addons + $tax;

    // Save to database if no errors
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO bookings 
                                  (room_id, guest_name, email, phone, check_in, check_out, persons, 
                                  special_requests, early_checkin, late_checkout, extra_bed, total_price) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("isssssisiiid", 
                $room_id,
                $guest_name,
                $email,
                $phone,
                $check_in,
                $check_out,
                $persons,
                $special_requests,
                $early_checkin,
                $late_checkout,
                $extra_bed,
                $total_price
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
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo htmlspecialchars($room['name']); ?> | Boboin.Aja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .error {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-teal-900 text-white">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img alt="Boboin.Aja logo" class="h-10 mr-3" src="Logo.png">
            </div>
            <nav class="space-x-6">
                <a class="hover:text-gray-300" href="home.html">Home</a>
                <a class="hover:text-gray-300" href="rooms.php">Rooms</a>
                <a class="hover:text-gray-300" href="facilities.html">Facilities</a>
                <a class="hover:text-gray-300" href="#">Contact</a>
            </nav>
            <button id="openPopup" class="bg-white text-teal-900 px-4 py-2 rounded hover:bg-gray-200">Login / Sign Up</button>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-teal-900 mb-6">Complete Your Booking</h1>
        
        <!-- Room Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    <img src="<?php echo htmlspecialchars($room['name']).'.png'; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="w-full rounded-lg">
                </div>
                <div class="md:w-2/3">
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($room['name']); ?></h2>
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400 mr-4">
                            <i class="fas fa-star"></i>
                            <span class="ml-1 text-gray-700"><?php echo $room['rating']; ?></span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-user-friends mr-1"></i>
                            <span><?php echo $room['capacity']; ?> people</span>
                        </div>
                        <?php if ($room['breakfast_included']): ?>
                        <div class="flex items-center text-gray-700 ml-4">
                            <i class="fas fa-coffee mr-1"></i>
                            <span>Breakfast included</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <p class="text-xl font-bold text-teal-900"><?php echo formatRupiah($room['price']); ?> <span class="text-sm font-normal text-gray-500">/ night</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Guest Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Guest Information</h2>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="guest_name">Full Name</label>
                    <input type="text" id="guest_name" name="guest_name" value="<?php echo htmlspecialchars($_POST['guest_name'] ?? ''); ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    <?php if (isset($errors) && in_array("Full name is required", $errors)): ?>
                        <span class="error">* Full name is required</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    <?php if (isset($errors) && (in_array("Valid email is required", $errors) || in_array("Email is required", $errors))): ?>
                        <span class="error">* Valid email is required</span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="phone">Phone Number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 bg-gray-100 text-gray-700">+62</span>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                               class="w-full px-4 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    </div>
                    <?php if (isset($errors) && in_array("Phone number is required", $errors)): ?>
                        <span class="error">* Phone number is required</span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2" for="check_in">Check-in Date</label>
                        <input type="date" id="check_in" name="check_in" value="<?php echo htmlspecialchars($_POST['check_in'] ?? ''); ?>" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2" for="check_out">Check-out Date</label>
                        <input type="date" id="check_out" name="check_out" value="<?php echo htmlspecialchars($_POST['check_out'] ?? ''); ?>" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    </div>
                    <?php if (isset($errors) && in_array("Check-out date must be after check-in date", $errors)): ?>
                        <div class="col-span-2">
                            <span class="error">* Check-out date must be after check-in date</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="persons">Number of Guests</label>
                    <select id="persons" name="persons" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($_POST['persons']) && $_POST['persons'] == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="special_requests">Special Requests</label>
                    <textarea id="special_requests" name="special_requests" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <!-- Add-ons and Payment -->
            <div class="space-y-6">
                <!-- Add-ons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Add-ons</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="early_checkin" name="early_checkin" value="1" 
                                       class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500" <?php echo (isset($_POST['early_checkin']) && $_POST['early_checkin'] == '1') ? 'checked' : ''; ?>>
                                <label for="early_checkin" class="ml-2 text-gray-700">Early Check-in (11:00 AM)</label>
                            </div>
                            <span class="text-gray-700"><?php echo formatRupiah(350000); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="late_checkout" name="late_checkout" value="1" 
                                       class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500" <?php echo (isset($_POST['late_checkout']) && $_POST['late_checkout'] == '1') ? 'checked' : ''; ?>>
                                <label for="late_checkout" class="ml-2 text-gray-700">Late Check-out (3:00 PM)</label>
                            </div>
                            <span class="text-gray-700"><?php echo formatRupiah(350000); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="extra_bed" name="extra_bed" value="1" 
                                       class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500" <?php echo (isset($_POST['extra_bed']) && $_POST['extra_bed'] == '1') ? 'checked' : ''; ?>>
                                <label for="extra_bed" class="ml-2 text-gray-700">Extra Bed</label>
                            </div>
                            <span class="text-gray-700"><?php echo formatRupiah(100000); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-700"><?php echo htmlspecialchars($room['name']); ?> (1 night)</span>
                            <span class="text-gray-700"><?php echo formatRupiah($room['price']); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-700">Add-ons</span>
                            <span class="text-gray-700" id="addons-total">Rp 0</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-700">Tax (10%)</span>
                            <span class="text-gray-700" id="tax-amount"><?php echo formatRupiah($room['price'] * 0.1); ?></span>
                        </div>
                        
                        <div class="border-t border-gray-200 my-3"></div>
                        
                        <div class="flex justify-between font-bold text-lg">
                            <span class="text-gray-800">Total</span>
                            <span class="text-teal-900" id="total-price"><?php echo formatRupiah($room['price'] * 1.1); ?></span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-teal-900 text-white py-3 rounded-lg font-bold mt-6 hover:bg-teal-800 transition duration-200">
                        Complete Booking
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-teal-900 text-white py-12 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <div class="flex justify-center">
                    <div class="w-48">
                        <img src="Logo.png" alt="Boboin.Aja logo" class="w-full h-auto">
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Boboin.Aja</h3>
                    <p class="text-gray-300 text-justify">
                        Our hotel is designed for those who seek comfort, relaxation, and a deep connection with nature.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                    <p class="text-gray-300 text-justify">
                        We proudly maintain a 100% smoke-free environment, preserving the purity of the air.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                    <p class="text-gray-300 text-justify">
                        The perfect place to unwind, recharge, and embrace the beauty of the outdoors.
                    </p>
                </div>
            </div>
            <div class="text-center mt-10 pt-4 border-t border-teal-800">
                <p class="text-gray-400">
                    © Copyright Boboin.Aja, All right reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Calculate and update prices when add-ons change
        const basePrice = <?php echo $room['price']; ?>;
        const earlyCheckin = document.getElementById('early_checkin');
        const lateCheckout = document.getElementById('late_checkout');
        const extraBed = document.getElementById('extra_bed');
        const addonsTotal = document.getElementById('addons-total');
        const taxAmount = document.getElementById('tax-amount');
        const totalPrice = document.getElementById('total-price');
        
        function calculateTotal() {
            let addons = 0;
            if (earlyCheckin.checked) addons += 350000;
            if (lateCheckout.checked) addons += 350000;
            if (extraBed.checked) addons += 100000;
            
            const subtotal = basePrice + addons;
            const tax = subtotal * 0.1;
            const total = subtotal + tax;
            
            addonsTotal.textContent = 'Rp ' + addons.toLocaleString('id-ID');
            taxAmount.textContent = 'Rp ' + Math.round(tax).toLocaleString('id-ID');
            totalPrice.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
        }
        
        earlyCheckin.addEventListener('change', calculateTotal);
        lateCheckout.addEventListener('change', calculateTotal);
        extraBed.addEventListener('change', calculateTotal);
        
        // Initialize calculation
        calculateTotal();
    </script>
</body>
</html>