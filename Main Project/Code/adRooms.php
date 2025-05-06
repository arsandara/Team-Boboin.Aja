<?php
include 'connection.php';

$search = $_GET['search'] ?? '';
$searchQuery = "";
if (!empty($search)) {
    $safeSearch = $conn->real_escape_string($search);
    $searchQuery = "AND guest_name LIKE '%$safeSearch%'";
}

$sql = "
    SELECT * FROM reservations
    WHERE status = 'Checked In' $searchQuery
    ORDER BY check_in ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boboin.Aja - Rooms</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-teal-900 text-white w-64 flex flex-col p-5 fixed h-full">
      <div class="flex items-center justify-center mb-6">
        <img src="Logo.png" alt="Boboin.Aja Logo" class="h-12">
      </div>
      <nav class="space-y-3">
        <a href="dashboard.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
          <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span>
        </a>
        <a href="reservation.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
          <i class="fas fa-calendar-alt w-6 text-center mr-3"></i><span>Reservations</span>
        </a>
        <a href="adRooms.php" class="flex items-center py-3 px-4 rounded-lg bg-teal-700">
          <i class="fas fa-bed w-6 text-center mr-3"></i><span>Rooms</span>
        </a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1">
      <!-- Header -->
      <div class="bg-teal-800 text-white p-6">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold">Rooms</h1>
          <div id="currentDate">Today, <?php echo date('F j, Y'); ?></div>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="bg-white p-4 shadow-sm">
        <div class="relative w-full">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input type="text" id="searchInput" onkeyup="searchGuest()" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Search guest name...">
        </div>
      </div>

      <!-- Booking Cards -->
      <div class="p-6 space-y-6" id="bookingContainer">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded-lg shadow-md p-6 booking-card">
          <div class="flex justify-between items-start mb-4">
            <div>
              <h2 class="text-lg font-semibold">Booking Information</h2>
              <div class="flex items-center mt-1 text-green-600">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="font-medium">Booking Confirmed</span>
              </div>
              <p class="text-gray-500 mt-1">Check In: <?php echo date('F d, Y', strtotime($row['check_in'])); ?></p>
            </div>
            <div class="text-right">
              <p class="text-gray-600 font-medium">Booking ID: MR-<?php echo str_pad($row['reservation_id'], 4, '0', STR_PAD_LEFT); ?></p>
            </div>
          </div>

          <!-- Guest -->
          <div class="mb-4">
            <h3 class="font-medium text-gray-700 mb-1">Guest</h3>
            <p class="font-semibold guest-name"><?php echo $row['guest_name']; ?></p>
            <p class="text-gray-600 text-sm"><?php echo $row['guest_email']; ?></p>
          </div>

          <!-- Booking Table -->
          <table class="w-full text-left mb-4">
            <thead>
              <tr class="border-b">
                <th class="pb-2">Room</th>
                <th class="pb-2">Room Number</th>
                <th class="pb-2">Guest</th>
                <th class="pb-2">Check In</th>
                <th class="pb-2">Check Out</th>
                <th class="pb-2">Durations</th>
                <th class="pb-2">Request</th>
                <th class="pb-2">Price</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b">
                <td class="py-3"><?php echo $row['room_name']; ?></td>
                <td class="py-3"><?php echo $row['room_number']; ?></td>
                <td class="py-3"><?php echo $row['person']; ?> Adult(s)</td>
                <td class="py-3"><?php echo date('M d, Y', strtotime($row['check_in'])); ?> <br><span class="text-xs text-gray-500">14:00 WIB</span></td>
                <td class="py-3"><?php echo date('M d, Y', strtotime($row['check_out'])); ?> <br><span class="text-xs text-gray-500">12:00 WIB</span></td>
                <td class="py-3"><?php echo $row['duration']; ?> Nights</td>
                <td class="py-3">
                  <?php
                    $requests = [];
                    if ($row['early_checkin']) $requests[] = 'Early Check In';
                    if ($row['late_checkout']) $requests[] = 'Late Check Out';
                    if ($row['extra_bed']) $requests[] = 'Extra Bed';
                    echo empty($requests) ? '-' : implode(', ', $requests);
                  ?>
                </td>
                <td class="py-3">Rp. <?php echo number_format($row['base_price'], 0, ',', '.'); ?>/night</td>
              </tr>
            </tbody>
          </table>

          <!-- Price Summary -->
          <div class="bg-gray-50 p-4 rounded">
            <h3 class="text-lg font-semibold mb-2">Price Summary</h3>
            <span class="bg-green-500 text-white px-2 py-1 rounded text-sm font-medium">Paid</span>
            <div class="mt-2 space-y-1">
              <div class="flex justify-between text-sm">
                <span>Room (<?php echo $row['duration']; ?> nights)</span>
                <span>Rp. <?php echo number_format($row['base_price'], 0, ',', '.'); ?></span>
              </div>
              <?php if ($row['request_price'] > 0): ?>
              <div class="flex justify-between text-sm">
                <span>Add On</span>
                <span>Rp. <?php echo number_format($row['request_price'], 0, ',', '.'); ?></span>
              </div>
              <?php endif; ?>
              <div class="flex justify-between text-sm">
                <span>Tax 10%</span>
                <span>Rp. <?php echo number_format($row['tax'], 0, ',', '.'); ?></span>
              </div>
              <div class="flex justify-between font-bold pt-2 border-t">
                <span>Total</span>
                <span>Rp. <?php echo number_format($row['total_price'], 0, ',', '.'); ?></span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-6 flex justify-end">
            <form action="checkout.php" method="POST" onsubmit="return confirm('Are you sure you want to check out this guest?');">
              <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
              <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium">Check Out</button>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <script>
    function searchGuest() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const cards = document.getElementsByClassName('booking-card');

      for (let card of cards) {
        const guestName = card.querySelector('.guest-name').textContent.toLowerCase();
        card.style.display = guestName.includes(input) ? '' : 'none';
      }
    }
  </script>
</body>
</html>
