<?php
include 'connection.php'; // Pastikan file koneksi sudah ada

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Ambil data reservasi berdasarkan booking_id
    $query = "SELECT * FROM reservations WHERE booking_id = '$booking_id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "Booking ID tidak ditemukan.";
    exit;
}

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $guest_name = $_POST['guest_name'];
    $email = $_POST['email'];
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $request = $_POST['request'];

    // Query update data
    $updateQuery = "UPDATE reservations SET 
                    guest_name='$guest_name', 
                    email='$email', 
                    room_type='$room_type', 
                    check_in='$check_in', 
                    check_out='$check_out', 
                    request='$request'
                    WHERE booking_id='$booking_id'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='reservation.php';</script>";
    } else {
        echo "Gagal update data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
</head>
<body>
    <h2>Edit Reservation</h2>
    <form method="POST">
        <label>Guest Name:</label>
        <input type="text" name="guest_name" value="<?= $data['guest_name'] ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required><br>

        <label>Room Type:</label>
        <input type="text" name="room_type" value="<?= $data['room_type'] ?>" required><br>

        <label>Check-In:</label>
        <input type="datetime-local" name="check_in" value="<?= $data['check_in'] ?>" required><br>

        <label>Check-Out:</label>
        <input type="datetime-local" name="check_out" value="<?= $data['check_out'] ?>" required><br>

        <label>Request:</label>
        <input type="text" name="request" value="<?= $data['request'] ?>"><br>

        <button type="submit" name="update">Update</button>
        <a href="reservation.php">Cancel</a>
    </form>
</body>
</html>
