<?php
require 'connection.php';

$search = $_GET['search'] ?? '';
$searchQuery = "";

if (!empty($search)) {
    $safeSearch = $conn->real_escape_string($search);
    $searchQuery = " AND guest_name LIKE '%$safeSearch%'";
}

$sql = "
    SELECT * FROM reservations 
    WHERE status IN ('Confirmed', 'Checked In') 
    $searchQuery
    ORDER BY check_in ASC
";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Boboin.Aja - Reservations</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-teal-900 text-white w-64 flex flex-col p-5 fixed h-full">
      <div class="flex items-center justify-center mb-6">
        <img src="Logo.png" alt="Boboin.Aja Logo" class="h-12" />
      </div>
      <nav class="space-y-3">
        <a href="dashboard.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
          <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span>
        </a>
        <a href="reservation.php" class="flex items-center py-3 px-4 rounded-lg bg-teal-700">
          <i class="fas fa-calendar-alt w-6 text-center mr-3"></i><span>Reservations</span>
        </a>
        <a href="adRooms.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
          <i class="fas fa-bed w-6 text-center mr-3"></i><span>Rooms</span>
        </a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1">
      <!-- Header -->
      <div class="bg-teal-800 text-white p-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Reservation</h1>
        <div id="currentDate"><?php echo "Today: " . date("F j, Y"); ?></div>
      </div>

      <!-- Search Bar -->
        <div class="bg-white p-4 shadow-sm">
        <form method="GET" action="reservation.php" class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            placeholder="Search guest name...">
        </form>
        </div>


      <!-- Reservation Cards -->
      <div class="p-6 space-y-6">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h2 class="text-lg font-semibold">Booking Information</h2>
                <div class="flex items-center mt-1 text-green-600">
                  <i class="fas fa-check-circle mr-2"></i>
                  <span class="font-medium">Booking Confirmed</span>
                </div>
                <p class="text-gray-500 mt-1"><?php echo date('F d, Y', strtotime($row['created_at'])); ?></p>
              </div>
              <div class="text-right text-gray-600 font-medium">
                Booking ID: MR-<?php echo strtoupper(substr($row['room_name'], 0, 2)) . str_pad($row['reservation_id'], 3, '0', STR_PAD_LEFT); ?>
              </div>
            </div>

            <div class="mb-4">
              <h3 class="font-medium text-gray-700 mb-2">Guest</h3>
              <p class="font-semibold"><?php echo $row['guest_name']; ?></p>
              <p class="text-gray-600"><?php echo $row['guest_email']; ?></p>
            </div>

            <table class="w-full text-left border-collapse mb-4">
              <thead>
                <tr class="border-b">
                  <th class="pb-2">Room</th>
                  <th class="pb-2">Room Number</th>
                  <th class="pb-2">Person</th>
                  <th class="pb-2">Check In</th>
                  <th class="pb-2">Check Out</th>
                  <th class="pb-2">Durations</th>
                  <th class="pb-2">Request</th>
                  <th class="pb-2">Price</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b">
                  <td class="py-3 font-semibold"><?php echo $row['room_name']; ?></td>
                  <td class="py-3"><?php echo $row['room_number']; ?></td>
                  <td class="py-3"><?php echo $row['person']; ?> Adults</td>
                  <td class="py-3"><?php echo date('M d, Y', strtotime($row['check_in'])) . " 14:00 WIB"; ?></td>
                  <td class="py-3"><?php echo date('M d, Y', strtotime($row['check_out'])) . " 12:00 WIB"; ?></td>
                  <td class="py-3"><?php echo $row['duration']; ?> Nights</td>
                  <td class="py-3">
                    <?php
                      $requests = [];
                      if ($row['early_checkin']) $requests[] = 'Early Check In';
                      if ($row['late_checkout']) $requests[] = 'Late Check Out';
                      if ($row['extra_bed']) $requests[] = 'Extra Bed';
                      echo !empty($requests) ? implode(', ', $requests) : '-';
                    ?>
                  </td>
                  <td class="py-3 font-semibold">Rp. <?php echo number_format($row['base_price'], 0, ',', '.'); ?>/night</td>
                </tr>
              </tbody>
            </table>

            <!-- Price Summary -->
            <div class="mt-1 bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-semibold mb-3">Price Summary</h3>
              <span class="bg-green-500 text-white px-2 py-1 rounded text-sm font-medium">Paid</span>
              <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Room (<?php echo $row['duration']; ?> nights):</span>
                  <span class="font-semibold">Rp. <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></span>
                </div>
                <?php if ($row['request_price'] > 0): ?>
                <div class="flex justify-between">
                  <span class="text-gray-600">Add On:</span>
                  <span class="font-semibold">Rp. <?php echo number_format($row['request_price'], 0, ',', '.'); ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between">
                  <span class="text-gray-600">Tax 10%:</span>
                  <span class="font-semibold">Rp. <?php echo number_format($row['tax'], 0, ',', '.'); ?></span>
                </div>
                <div class="flex justify-between font-bold">
                  <span class="text-gray-700">Total:</span>
                  <span>Rp. <?php echo number_format($row['total_price'], 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
              <a href="update.php?reservation_id=<?= $row['reservation_id']; ?>">
                <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded font-medium transition duration-200">
                  Edit
                </button>
              </a>
              <a href="checkin.php?id=<?= $row['reservation_id']; ?>">
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-medium">Check In</button>
              </a>
              <a href="cancel.php?id=<?= $row['reservation_id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium">Cancel Booking</button>
              </a>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

</body>
</html>
