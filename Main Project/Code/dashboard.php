<?php
require 'connection.php';

// Ambil occupancy per room_name berdasarkan status "Checked In"
$occupancyQuery = "
    SELECT 
        r.room_name,
        r.total_rooms,
        COUNT(rv.reservation_id) AS occupied_rooms
    FROM rooms r
    LEFT JOIN reservations rv 
        ON rv.room_name = r.room_name AND rv.status = 'Checked In'
    GROUP BY r.room_name, r.total_rooms
";
$occupancyResult = $conn->query($occupancyQuery);

$occupancyData = [];
$totalRooms = 0;
$totalOccupied = 0;

while ($row = $occupancyResult->fetch_assoc()) {
    $roomName = $row['room_name'];
    $occupied = (int)$row['occupied_rooms'];
    $total = (int)$row['total_rooms'];
    $available = $total - $occupied;

    $occupancyData[] = [
        'room_name' => $roomName,
        'occupied' => $occupied,
        'available' => $available,
        'total' => $total,
        'sold_out' => $available <= 0
    ];

    $totalRooms += $total;
    $totalOccupied += $occupied;
}

// Hari ini (format Y-m-d)
$today = date('Y-m-d');

// Today's Guest = jumlah yang check_in hari ini
$todayGuestQuery = "SELECT COUNT(*) AS total FROM reservations WHERE check_in = ?";
$stmtToday = $conn->prepare($todayGuestQuery);
$stmtToday->bind_param("s", $today);
$stmtToday->execute();
$stmtToday->bind_result($todayGuest);
$stmtToday->fetch();
$stmtToday->close();

// New Bookings = jumlah booking yang dibuat hari ini
$newBookingQuery = "SELECT COUNT(*) AS total FROM reservations WHERE DATE(created_at) = ?";
$stmtBooking = $conn->prepare($newBookingQuery);
$stmtBooking->bind_param("s", $today);
$stmtBooking->execute();
$stmtBooking->bind_result($newBooking);
$stmtBooking->fetch();
$stmtBooking->close();

// Revenue Today = total harga dari reservasi dengan check-in hari ini
$revenueQuery = "SELECT SUM(total_price) AS total FROM reservations WHERE check_in = ?";
$stmtRevenue = $conn->prepare($revenueQuery);
$stmtRevenue->bind_param("s", $today);
$stmtRevenue->execute();
$stmtRevenue->bind_result($revenueToday);
$stmtRevenue->fetch();
$stmtRevenue->close();
$revenueToday = $revenueToday ?? 0;

// Recent Bookings = ambil max 5 yang statusnya Confirmed dan urut dari paling baru
$bookings = [];
$recentQuery = "SELECT reservation_id, guest_name, room_name, check_in, status FROM reservations WHERE status = 'Confirmed' ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($recentQuery);
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Hitung Room Availability Percentage
$availabilityPercentage = $totalRooms > 0 
    ? round(($totalRooms - $totalOccupied) / $totalRooms * 100)
    : 0;



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boboin.Aja - Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  </head>
  <body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
      <div class="bg-teal-900 text-white w-64 flex flex-col p-5 fixed h-full">
        <div class="flex items-center justify-center mb-6">
          <img src="Logo.png" alt="Boboin.Aja Logo" class="h-12" />
        </div>
        <nav class="space-y-3">
          <a href="dashboard.php" class="flex items-center py-3 px-4 rounded-lg bg-teal-700">
            <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span>
          </a>
          <a href="reservation.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
            <i class="fas fa-calendar-alt w-6 text-center mr-3"></i><span>Reservations</span>
          </a>
          <a href="adRooms.php" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
            <i class="fas fa-bed w-6 text-center mr-3"></i><span>Rooms</span>
          </a>
        </nav>
      </div>

      <div class="ml-64 flex-1">
        <div class="bg-teal-800 text-white p-6">
          <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <div id="currentDate">Today: <?php echo date('d F Y'); ?></div>
          </div>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
              <i class="fas fa-user-friends text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">TODAY'S GUEST</p>
                <h2 class="text-2xl font-bold"><?php echo $todayGuest; ?></h2>
              </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
              <i class="fas fa-calendar-plus text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">NEW BOOKINGS</p>
                <h2 class="text-2xl font-bold"><?php echo $newBooking; ?></h2>
              </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
              <i class="fas fa-hotel text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">ROOM AVAILABILITY</p>
                <h2 class="text-2xl font-bold"><?php echo $availabilityPercentage; ?>%</h2>
              </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
              <i class="fas fa-dollar-sign text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">REVENUE (TODAY)</p>
                <h2 class="text-2xl font-bold">Rp. <?php echo number_format($revenueToday, 0, ',', '.'); ?></h2>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-6 mt-6">
            <div class="col-span-2 bg-white p-6 rounded-lg shadow">
              <h2 class="text-lg font-semibold mb-4">Recent Bookings</h2>
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr>
                    <th class="p-2 border-b">Guest</th>
                    <th class="p-2 border-b">Room</th>
                    <th class="p-2 border-b">Check-in</th>
                    <th class="p-2 border-b">Status</th>
                    <th class="p-2 border-b">Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $booking): ?>
                  <tr>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                    <td class="p-2 border-b"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                    <td class="p-2 border-b text-yellow-600"><?php echo htmlspecialchars($booking['status']); ?></td>
                    <td class="p-2 border-b space-x-2">
                        <!-- Trigger -->
                        <button onclick="openEditModal(<?= $booking['reservation_id'] ?>)" class="bg-yellow-500 text-white px-3 py-2 rounded">Edit</button>
                        <!-- Modal -->
                        <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                          <div class="bg-white p-6 rounded w-[400px] space-y-4">
                            <h2 class="text-xl font-bold">Edit Reservation</h2>
                            <p><strong>Previous Check-In:</strong> <span id="prevCheckIn"></span></p>
                            <p><strong>Previous Check-Out:</strong> <span id="prevCheckOut"></span></p>
                            <p><strong>Previous Requests:</strong> <span id="prevRequest"></span></p>
                            <input type="date" id="editCheckIn" class="w-full border rounded p-2" />
                            <input type="date" id="editCheckOut" class="w-full border rounded p-2" />
                            <button onclick="submitEdit()" class="bg-teal-700 text-white px-4 py-2 rounded">Save</button>
                            <button onclick="closeModal()" class="text-red-500">Cancel</button>
                          </div>
                        </div>
                      </a>
                      <button onclick="deleteReservation(<?= $booking['reservation_id'] ?>)" class="bg-red-500 text-white px-3 py-2 rounded">
                        Delete
                      </button>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
              <h2 class="text-lg font-semibold mb-4">
                <i class="fas fa-bed"></i> Room Occupancy
              </h2>
              <ul>
                <?php foreach ($occupancyData as $room): ?>
                  <li class="<?= $room['sold_out'] ? 'text-red-600 font-bold' : 'text-green-600' ?>">
                    <?= htmlspecialchars($room['room_name']) ?> - <?= $room['occupied'] ?>/<?= $room['total'] ?> rooms
                    <?= $room['sold_out'] ? '- SOLD OUT' : '' ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
    function deleteReservation(id) {
        if (confirm("Are you sure you want to delete this reservation?")) {
            fetch(`http://localhost:8000/api/reservations/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("Failed to delete");
                return response.json();
            })
            .then(data => {
                alert("Reservation deleted successfully.");
                location.reload();
            })
            .catch(error => {
                alert("Error deleting reservation.");
                console.error(error);
            });
        }
    }
    let currentId = null;
    function openEditModal(id) {
        currentId = id;
        fetch(`http://localhost:8000/api/reservations/${id}`)
          .then(res => res.json())
          .then(data => {
              document.getElementById("editCheckIn").value = data.check_in;
              document.getElementById("editCheckOut").value = data.check_out;
              document.getElementById("prevCheckIn").textContent = data.check_in;
              document.getElementById("prevCheckOut").textContent = data.check_out;

              let requests = [];
              if (data.early_checkin) requests.push("Early Check In");
              if (data.late_checkout) requests.push("Late Check Out");
              if (data.extra_bed) requests.push("Extra Bed");
              document.getElementById("prevRequest").textContent = requests.join(', ') || 'None';

              document.getElementById("editModal").classList.remove("hidden");
          });
    }
    function closeModal() {
        document.getElementById("editModal").classList.add("hidden");
    }
    function submitEdit() {
        const checkIn = document.getElementById("editCheckIn").value;
        const checkOut = document.getElementById("editCheckOut").value;

        fetch(`http://localhost:8000/api/reservations/${currentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ check_in: checkIn, check_out: checkOut })
        })
        .then(res => res.json())
        .then(data => {
            alert("Reservation updated!");
            closeModal();
            location.reload();
        })
        .catch(err => {
            alert("Failed to update reservation.");
            console.error(err);
        });
    }
    </script>
  </body>
</html>

