<?php
include 'connection.php';


$sql = "SELECT id, guest_name, room_type, check_in_date, check_out_date, status, room_number, person, price, duration, request FROM reservations ORDER BY check_in_date DESC";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boboin.Aja - Reservation</title>
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
                <a href="admin.html" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
                    <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="reservation.html" class="flex items-center py-3 px-4 rounded-lg bg-teal-700">
                    <i class="fas fa-calendar-alt w-6 text-center mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="adRooms.html" class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200">
                    <i class="fas fa-bed w-6 text-center mr-3"></i>
                    <span>Rooms</span>
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <!-- Green Header Section -->
            <div class="bg-teal-800 text-white p-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold">Reservation</h1>
                    <div id="currentDate">Today, March 28, 2025</div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="bg-white p-4 shadow-sm">
                <div class="relative w-full">   
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Search reservations...">
                </div>
            </div>
            
            <!-- Booking Card -->
            <div class="p-6">
                <!-- Booking Header -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                        <h2 class="text-lg font-semibold">Booking Information</h2>
                        <div class="flex items-center mt-1 text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium">Booking Confirmed</span>
                        </div>
                        <p class="text-gray-500 mt-1">March 11, 2025</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 font-medium">Booking ID: MR-PW1114</p>
                    </div>
                </div>
                
                <!-- Guest Info -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-2">Guest</h3>
                    <p class="font-semibold">Ara Arsanda</p>
                    <p class="text-gray-600">ara@gmail.com</p>
                </div>
                
                <!-- Booking Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2 pr-4">Room</th>
                                <th class="pb-2 pr-4">Room Number</th>
                                <th class="pb-2 pr-4">Person</th>
                                <th class="pb-2 pr-4">Check In</th>
                                <th class="pb-2 pr-4">Check Out</th>
                                <th class="pb-2 pr-4">Durations</th>
                                <th class="pb-2 pr-4">Request</th>
                                <th class="pb-2">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="py-3 pr-4 font-semibold">2 Paws Cabin</td>
                                <td class="py-3 pr-4 font-semibold">158</td>
                                <td class="py-3 pr-4 font-semibold">2 Adults</td>
                                <td class="py-3 pr-4">
                                    <span class="font-semibold block">Mar 11, 2025</span>
                                    <span class="text-sm text-gray-500">14:00 WIB</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="font-semibold block">Mar 14, 2025</span>
                                    <span class="text-sm text-gray-500">15:00 WIB</span>
                                </td>
                                <td class="py-3 pr-4 font-semibold">3 Nights</td>
                                <td class="py-3 pr-4 font-semibold">Late Check Out</td>
                                <td class="py-3 font-semibold">750.000/night</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Price Summary -->
                <div class="mt-1 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Price Summary</h3>
                    <span class="bg-green-500 text-white px-2 py-1 rounded text-sm font-medium">Paid</span>
                    
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Room (3 nights):</span>
                            <span class="font-semibold">Rp. 2.250.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Add On: Late Check In</span>
                            <span class="font-semibold">Rp. 350.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold" id="subtotal">Rp. 2.600.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (10%):</span>
                            <span class="font-semibold" id="tax">Rp. 260.000</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t">
                            <span class="text-gray-600 font-bold">TOTAL:</span>
                            <span class="font-bold text-lg" id="total">Rp. 2.860.000</span>
                        </div>
                    </div>
                </div>

                <script>
                    // Automatic calculation
                    const roomPrice = 2250000; // 2.250K
                    const addOnPrice = 350000; // 350K
                    const subtotal = roomPrice + addOnPrice;
                    const tax = subtotal * 0.1; // 10%
                    const total = subtotal + tax;
                    
                    // Format to Indonesian currency format
                    function formatCurrency(amount) {
                        return 'Rp. ' + amount.toLocaleString('id-ID');
                    }
                    
                    // Update the displayed values
                    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
                    document.getElementById('tax').textContent = formatCurrency(tax);
                    document.getElementById('total').textContent = formatCurrency(total);
                </script>
                
                <!-- Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium transition duration-200">
                        Check Out
                    </button>
                </div>
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