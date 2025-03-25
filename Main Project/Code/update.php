<?php
// Data Dummy (Biasanya ini dari database)
$reservation = [
    "id" => "MR-PW1114",
    "guest_name" => "Ara Arsanda",
    "room" => "2 Paws Cabin",
    "room_number" => "158",
    "persons" => "2 Adults",
    "check_in" => "2025-03-11T14:00",
    "check_out" => "2025-03-14T15:00",
    "request" => "Late Check Out",
    "price" => "100000000"
];

// Jika form dikirim, update data dummy
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation["guest_name"] = $_POST["guest_name"];
    $reservation["room"] = $_POST["room"];
    $reservation["room_number"] = $_POST["room_number"];
    $reservation["persons"] = $_POST["persons"];
    $reservation["check_in"] = $_POST["check_in"];
    $reservation["check_out"] = $_POST["check_out"];
    $reservation["request"] = $_POST["request"];
    $reservation["price"] = $_POST["price"];

    echo "<script>alert('Data Updated (Dummy Mode)!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation (Dummy)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex justify-center items-center h-screen">
        <form method="POST" class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-lg font-semibold mb-4">Edit Reservation (Dummy)</h2>

            <label class="block text-gray-700">Guest Name:</label>
            <input type="text" name="guest_name" value="<?= $reservation['guest_name']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Room Type:</label>
            <input type="text" name="room" value="<?= $reservation['room']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Room Number:</label>
            <input type="number" name="room_number" value="<?= $reservation['room_number']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Persons:</label>
            <input type="text" name="persons" value="<?= $reservation['persons']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Check-In:</label>
            <input type="datetime-local" name="check_in" value="<?= $reservation['check_in']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Check-Out:</label>
            <input type="datetime-local" name="check_out" value="<?= $reservation['check_out']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-2">

            <label class="block text-gray-700">Special Request:</label>
            <textarea name="request" class="w-full p-2 border border-gray-300 rounded-lg mb-2"><?= $reservation['request']; ?></textarea>

            <label class="block text-gray-700">Price per Night:</label>
            <input type="number" name="price" value="<?= $reservation['price']; ?>" required class="w-full p-2 border border-gray-300 rounded-lg mb-4">

            <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded">Update (Dummy)</button>
        </form>
    </div>
</body>
</html>
