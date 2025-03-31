<?php
include 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    
    $sql = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Reservation deleted successfully!'); window.location.href='admin.html';</script>";
    } else {
        echo "<script>alert('Error deleting reservation!'); window.location.href='admin.html';</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='admin.html';</script>";
}
?>