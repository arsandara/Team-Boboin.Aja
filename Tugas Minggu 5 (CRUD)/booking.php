<?php
// ========================
// DATABASE CONFIGURATION
// ========================
require 'connection.php';

// Ambil data kamar dari database berdasarkan room_id
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch();
    
    if (!$room) {
        die("Room not found");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Proses form booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    $errors = [];
    $guest_name = trim($_POST['guest_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $persons = intval($_POST['persons'] ?? 1);
    $special_requests = trim($_POST['special_requests'] ?? '');
    
    if (empty($guest_name)) $errors[] = "Full name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($check_in)) $errors[] = "Check-in date is required";
    if (empty($check_out)) $errors[] = "Check-out date is required";
    if ($check_in >= $check_out) $errors[] = "Check-out date must be after check-in date";
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings 
                                  (room_id, guest_name, email, check_in, check_out, persons, special_requests, total_price) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $total_price = $room['price']; // Anda bisa menambahkan logika kalkulasi harga disini
            
            $stmt->execute([
                $room_id,
                $guest_name,
                $email,
                $check_in,
                $check_out,
                $persons,
                $special_requests,
                $total_price
            ]);
            
            // Redirect ke halaman sukses
            header("Location: booking_success.php?id=".$pdo->lastInsertId());
            exit;
            
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boboin.aja - Review Booking</title>
    <link rel="stylesheet" href="booking.css"> <!-- Hubungkan file CSS -->
</head>

<body>
    <div class="header">
        <img src="LOGO.png" alt="boboin.aja" class="logo">
        <div class="page-title">Review Booking</div>
        <div style="width: 100px;"></div> <!-- Placeholder div to balance the layout -->
    </div>

    <div class="container">
        <div class="booking-details">
            <div class="booking-detail-item">
                <h4>Check In</h4>
                <p>October 10, 2025 (14.00 WIB)</p>
            </div>
            <div class="booking-detail-item">
                <h4>Check Out</h4>
                <p>October 11, 2025 (12.00 WIB)</p>
            </div>
            <div class="booking-detail-item">
                <h4>Person</h4>
                <p>02 Person</p>
            </div>
        </div>

        <div class="room-card">
            <div class="room-image">
                <img src="Family Cabin with Jacuzzi.png" alt="Family Cabin With Jacuzzi">
            </div>
            <div class="room-info">
                <h3 class="room-title">Family Cabin With Jacuzzi</h3>
                <div class="room-rating">
                    <span class="star">★</span>
                    <span>4.9 (1K+ Reviews)</span>
                </div>
                <div class="room-facilities">
                    <div class="facility">
                        <span>👤 4 peoples</span>
                    </div>
                    <div class="facility">
                        <span>🍳 Breakfast</span>
                    </div>
                </div>
                <div class="room-price">Rp. 3.500.000</div>
            </div>
        </div>

        <h3 class="section-title">Personal Information</h3>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="form-container">
            <div class="form-group">
                <label class="required" for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo $firstName; ?>">
                <span class="error"><?php echo $firstNameErr ? "* $firstNameErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo $lastName; ?>">
            </div>

            <div class="form-group">
                <label class="required" for="dateOfBirth">Date of Birth</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>">
                <span class="error"><?php echo $dateOfBirthErr ? "* $dateOfBirthErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label class="required" for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label class="required" for="phoneNumber">Phone Number</label>
                <div class="phone-input">
                    <div class="country-code">
                        <span>🇮🇩</span>
                        <span>+62</span>
                    </div>
                    <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber; ?>">
                </div>
                <span class="error"><?php echo $phoneNumberErr ? "* $phoneNumberErr" : ""; ?></span>
            </div>

            <h3 class="section-title">Add On Request</h3>
            <div class="add-on-item">
                <div class="checkbox-container">
                    <input type="checkbox" id="earlyCheckIn" name="earlyCheckIn" <?php echo $earlyCheckIn ? 'checked' : ''; ?>>
                    <label for="earlyCheckIn">Early Check In (11.00 WIB)</label>
                </div>
                <div class="add-on-price">Rp. 350.000</div>
            </div>

            <div class="add-on-item">
                <div class="checkbox-container">
                    <input type="checkbox" id="lateCheckOut" name="lateCheckOut" <?php echo $lateCheckOut ? 'checked' : ''; ?>>
                    <label for="lateCheckOut">Late Check Out (15.00 WIB)</label>
                </div>
                <div class="add-on-price">Rp. 350.000</div>
            </div>

            <div class="add-on-item">
                <div class="checkbox-container">
                    <input type="checkbox" id="extraBed" name="extraBed" <?php echo $extraBed ? 'checked' : ''; ?>>
                    <label for="extraBed">Extra Bed</label>
                </div>
                <div class="add-on-price">Rp. 100.000</div>
            </div>

            <h3 class="section-title">Payment Details</h3>
                <div class="payment-details">
                    <div class="payment-row">
                        <div>Family Cabin with Jacuzzi (1 nights)</div>
                        <div>Rp. <?php echo formatRupiah($roomCost); ?></div>
                    </div>
                    <div class="payment-row">
                        <div>Request add-on</div>
                        <div>Rp. <?php echo formatRupiah($addOnCost); ?></div>
                    </div>
                    <div class="payment-row">
                        <div>Tax (10%)</div>
                        <div>Rp. <?php echo formatRupiah($tax); ?></div>
                    </div>
                    <div class="total-row">
                        <div>Total</div>
                        <div>Rp. <?php echo formatRupiah($totalCost); ?></div>
                    </div>
                    
                    <button type="submit" class="qris-button" style="margin: 20px auto; display: block; width: fit-content;">
                        Book Now
                    </button>
                </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the add-on checkboxes
        const earlyCheckIn = document.getElementById('earlyCheckIn');
        const lateCheckOut = document.getElementById('lateCheckOut');
        const extraBed = document.getElementById('extraBed');
        
        // Add event listeners to checkboxes
        earlyCheckIn.addEventListener('change', updateTotals);
        lateCheckOut.addEventListener('change', updateTotals);
        extraBed.addEventListener('change', updateTotals);
        
        // Function to update totals
        // Function to update totals
        function updateTotals() {
            // Base room cost
            const roomCost = 3500000; // Changed to Rp. 3,500,000
            let addOnCost = 0;
            
            // Calculate add-on costs based on selections
            if (earlyCheckIn.checked) addOnCost += 350000; // Changed to Rp. 350,000
            if (lateCheckOut.checked) addOnCost += 350000; // Changed to Rp. 350,000
            if (extraBed.checked) addOnCost += 100000;     // Changed to Rp. 100,000
            
            // Calculate tax and total
            const tax = (roomCost + addOnCost) * 0.1;
            const totalCost = roomCost + addOnCost + tax;
            
            // Format numbers with commas for display
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            // Update the displayed values
            document.querySelector('.payment-row:nth-child(2) div:last-child').textContent = 'Rp. ' + formatNumber(addOnCost);
            document.querySelector('.payment-row:nth-child(3) div:last-child').textContent = 'Rp. ' + formatNumber(tax);
            document.querySelector('.total-row div:last-child').textContent = 'Rp. ' + formatNumber(totalCost);
        }
    });
</script>