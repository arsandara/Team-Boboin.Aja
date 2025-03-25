<?php
// Inisialisasi variabel untuk menyimpan nilai input dan error
$firstName = $lastName = $email = $phoneNumber =  $dateOfBirth = "";
$firstNameErr = $lastNameErr = $emailErr = $phoneNumberErr =  $dateOfBirthErr = "";
$earlyCheckIn = $lateCheckOut = $extraBed = false;
$addOns = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi First Name
    $firstName = $_POST["firstName"] ?? "";
    if (empty($firstName)) {
        $firstNameErr = "First Name wajib diisi";
    }

    // Validasi Email
    $email = $_POST["email"] ?? "";
    if (empty($email)) {
        $emailErr = "Email wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format email tidak valid";
    }

    // Validasi Phone Number
    $phoneNumber = $_POST["phoneNumber"] ?? "";
    if (empty($phoneNumber)) {
        $phoneNumberErr = "Nomor Telepon wajib diisi";
    } elseif (!ctype_digit(str_replace('+', '', $phoneNumber))) {
        $phoneNumberErr = "Nomor Telepon harus berupa angka";
    }

    // Validasi Date of Birth
    $dateOfBirth = $_POST["dateOfBirth"] ?? "";
    if (empty($dateOfBirth)) {
        $dateOfBirthErr = "Tanggal lahir wajib diisi";
    }

    // Last Name (optional)
    $lastName = $_POST["lastName"] ?? "";

    // Check add-on options
    $earlyCheckIn = isset($_POST["earlyCheckIn"]);
    $lateCheckOut = isset($_POST["lateCheckOut"]);
    $extraBed = isset($_POST["extraBed"]);
    
    if ($earlyCheckIn) $addOns[] = "Early Check In";
    if ($lateCheckOut) $addOns[] = "Late Check Out";
    if ($extraBed) $addOns[] = "Extra Bed";
}

// Initialize variables with default values
$roomCost = 3500000; // Rp 3.500.000
$addOnCost = 0;
$tax = 0;
$totalCost = 0;

// Calculate costs based on checkbox selections
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update add-on cost based on selections
    if (isset($_POST["earlyCheckIn"])) $addOnCost += 350000;
    if (isset($_POST["lateCheckOut"])) $addOnCost += 350000;
    if (isset($_POST["extraBed"])) $addOnCost += 100000;
}

// Calculate tax and total regardless of whether form was submitted
$tax = ($roomCost + $addOnCost) * 0.1; // 10% tax
$totalCost = $roomCost + $addOnCost + $tax;

// Format currency
function formatRupiah($number) {
    return number_format($number, 0, ',', '.');
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