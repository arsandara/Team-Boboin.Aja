<?php
// Include database connection
include 'connection.php';

// Fetch reservations from the database
$sql = "SELECT * FROM reservations ORDER BY check_in DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boboin.Aja - Reservations</title>
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
                <a href="admin.html"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
                    <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="reservation.php" class="flex items-center py-3 px-4 rounded-lg bg-teal-700">
                    <i class="fas fa-calendar-alt w-6 text-center mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="adRooms.html"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
                    <i class="fas fa-bed w-6 text-center mr-3"></i>
                    <span>Rooms</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="ml-64 flex-1 p-6">
            <div class="bg-teal-800 text-white p-6 flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Reservation</h1>
                <div id="currentDate"></div>
            </div>

            <div class="bg-white p-4 shadow-sm">
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                    placeholder="Search reservations...">
            </div>

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
                                <p class="text-gray-500 mt-1"><?php echo date('F d, Y', strtotime($row['booking_date'])); ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 font-medium">Booking ID: <?php echo $row['booking_id']; ?></p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-medium text-gray-700 mb-2">Guest</h3>
                            <p class="font-semibold"><?php echo $row['guest_name']; ?></p>
                            <p class="text-gray-600"><?php echo $row['guest_email']; ?></p>
                        </div>

                        <table class="w-full text-left border-collapse">
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
                                    <td class="py-3 font-semibold"><?php echo $row['room_type']; ?></td>
                                    <td class="py-3 font-semibold"><?php echo $row['room_number']; ?></td>
                                    <td class="py-3 font-semibold"><?php echo $row['guests']; ?></td>
                                    <td class="py-3 font-semibold">
                                        <?php echo date('M d, Y', strtotime($row['check_in'])); ?></td>
                                    <td class="py-3 font-semibold">
                                        <?php echo date('M d, Y', strtotime($row['check_out'])); ?></td>
                                    <td class="py-3 font-semibold"><?php echo $row['duration']; ?> Nights</td>
                                    <td class="py-3 font-semibold"><?php echo $row['special_request'] ?: '-'; ?></td>
                                    <td class="py-3 font-semibold">Rp.
                                        <?php echo number_format($row['price'], 0, ',', '.'); ?>/night</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-1 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Price Summary</h3>
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-sm font-medium">Paid</span>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Price:</span>
                                    <span class="font-semibold">Rp.
                                        <?php echo number_format($row['total_price'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="update.php?booking_id=<?= $row['booking_id']; ?>">
                                <button
                                    class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded font-medium transition duration-200">
                                    Edit
                                </button>
                            </a>
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-medium">Check
                                In</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium">Cancel
                                Booking</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        const today = new Date();
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('currentDate').textContent = `Today: ${today.toLocaleDateString('en-US', options)}`;
    </script>
</body>

</html>