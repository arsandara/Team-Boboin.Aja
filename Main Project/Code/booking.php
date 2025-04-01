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
    if ($early_checkin) $addons += 50000000;
    if ($late_checkout) $addons += 50000000;
    if ($extra_bed) $addons += 50000000;
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo htmlspecialchars($room['name']); ?> | Boboin.Aja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="booking.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .error {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .date-input {
            display: flex;
            gap: 0.5rem;
        }
        .date-input input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Header Section - Simplified -->
    <header class="bg-teal-900 text-white">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img alt="Boboin.Aja logo" class="h-10 mr-3" src="Logo.png">
            </div>
            <div class="text-xl font-bold">Review Booking</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto py-8 px-4 max-w-4xl"> <!-- Reduced max-width -->
    <div class="bg-white rounded-lg shadow-md p-6"> <!-- Reduced padding -->
        <!-- Room Information with Image -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <div class="md:w-1/3">
                    <img src="<?php echo htmlspecialchars($room['name']).'.png'; ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="w-full rounded-lg">
                </div>
                <div class="md:w-2/3">
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($room['name']); ?></h1>
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400 mr-4">
                            <i class="fas fa-sync-alt mr-1"></i>
                            <span class="ml-1 text-gray-700"><?php echo $room['rating']; ?> (IK+ Reviews)</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-sync-alt mr-1"></i>
                            <span><?php echo $room['capacity']; ?> peoples</span>
                        </div>
                        <?php if ($room['breakfast_included']): ?>
                        <div class="flex items-center text-gray-700 ml-4">
                            <i class="fas fa-sync-alt mr-1"></i>
                            <span>Breakfast</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-bold text-teal-900"><?php echo formatRupiah($room['price']); ?></p>
                    </div>
                </div>
            </div>
                
                <!-- Check In/Out Dates -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg mt-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Check In</label>
                    <div class="bg-white p-3 rounded-lg">
                        <input type="date" id="check_in" name="check_in" value="<?php echo htmlspecialchars($_POST['check_in'] ?? ''); ?>" 
                               class="w-full font-medium focus:outline-none" required>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Check Out</label>
                    <div class="bg-white p-3 rounded-lg">
                        <input type="date" id="check_out" name="check_out" value="<?php echo htmlspecialchars($_POST['check_out'] ?? ''); ?>" 
                               class="w-full font-medium focus:outline-none" required>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Person</label>
                    <div class="bg-white p-3 rounded-lg">
                        <select id="persons" name="persons" class="w-full font-medium focus:outline-none">
                            <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                                <option value="<?php echo $i; ?>">
                                    <?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

            <!-- Booking Form -->
            <form method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Personal Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-2" for="first_name">First Name*</label>
                    <input type="text" id="first_name" name="first_name" 
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2" for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" 
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2" for="date_of_birth">Date of Birth*</label>
                    <div class="date-input">
                        <input type="text" id="day" placeholder="dd" maxlength="2" class="text-center p-2 border rounded">
                        <input type="text" id="month" placeholder="mm" maxlength="2" class="text-center p-2 border rounded">
                        <input type="text" id="year" placeholder="yyyy" maxlength="4" class="text-center p-2 border rounded">
                    </div>
                    <input type="hidden" id="date_of_birth" name="date_of_birth">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2" for="email">Email*</label>
                    <input type="email" id="email" name="email" 
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2" for="phone">Phone Number*</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 bg-gray-100 text-gray-700">+62</span>
                        <input type="tel" id="phone" name="phone" 
                               class="w-full px-4 py-3 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add On Request -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Add On Request</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <input type="checkbox" id="early_checkin" name="early_checkin" value="1" class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500">
                        <label for="early_checkin" class="ml-3 text-gray-700 font-medium">Early Check In (11.00 WIB)</label>
                    </div>
                    <span class="text-gray-700 font-medium"><?php echo formatRupiah(350000); ?></span>
                </div>
                
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <input type="checkbox" id="late_checkout" name="late_checkout" value="1" class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500">
                        <label for="late_checkout" class="ml-3 text-gray-700 font-medium">Late Check Out (15.00 WIB)</label>
                    </div>
                    <span class="text-gray-700 font-medium"><?php echo formatRupiah(350000); ?></span>
                </div>
                
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <input type="checkbox" id="extra_bed" name="extra_bed" value="1" class="h-5 w-5 text-teal-600 rounded focus:ring-teal-500">
                        <label for="extra_bed" class="ml-3 text-gray-700 font-medium">Extra Bed</label>
                    </div>
                    <span class="text-gray-700 font-medium"><?php echo formatRupiah(100000); ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-6">Payment Details</h2>
            
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-700"><?php echo htmlspecialchars($room['name']); ?> (<span id="nights-count">1</span> nights)</span>
                        <span class="text-gray-700" id="room-price"><?php echo formatRupiah($room['price']); ?></span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-700">Request add-on</span>
                        <span class="text-gray-700" id="addons-total">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-700">Tax (10%)</span>
                        <span class="text-gray-700" id="tax-amount"><?php echo formatRupiah($room['price'] * 0.1); ?></span>
                    </div>
                    
                    <div class="border-t border-gray-300 my-4"></div>
                    
                    <div class="flex justify-between font-bold">
                        <span class="text-gray-800">Total</span>
                        <span class="text-teal-900" id="total-price"><?php echo formatRupiah($room['price'] * 1.1); ?></span>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-teal-900 text-white py-3 rounded-lg font-bold mt-8 hover:bg-teal-800 transition duration-200">
                    Complete Booking
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Calculate nights and update price
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const nightsCount = document.getElementById('nights-count');
    const roomPrice = document.getElementById('room-price');
    
    function calculateNights() {
        if (checkInInput.value && checkOutInput.value) {
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);
            const diffTime = checkOutDate - checkInDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                nightsCount.textContent = diffDays;
                const totalRoomPrice = <?php echo $room['price']; ?> * diffDays;
                roomPrice.textContent = 'Rp ' + totalRoomPrice.toLocaleString('id-ID');
                calculateTotal();
            }
        }
    }
    
    checkInInput.addEventListener('change', calculateNights);
    checkOutInput.addEventListener('change', calculateNights);
    
    // Update the calculateTotal function to include nights
    function calculateTotal() {
        const nights = parseInt(nightsCount.textContent) || 1;
        const basePrice = <?php echo $room['price']; ?> * nights;
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
</script>