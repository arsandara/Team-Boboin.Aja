<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_boboinaja';

// Membuat koneksi MySQLi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus echo "Connection Success"; // Tidak diperlukan untuk produksi
?>