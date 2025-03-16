<?php
// Data fasilitas dalam array asosiatif
$fasilitas = [
    ["nama" => "Kolam Renang", "deskripsi" => "Kolam renang outdoor dengan pemandangan indah."],
    ["nama" => "Restoran", "deskripsi" => "Restoran dengan menu khas dan suasana nyaman."],
    ["nama" => "Spa & Massage", "deskripsi" => "Layanan spa terbaik untuk relaksasi."],
    ["nama" => "Gym", "deskripsi" => "Fasilitas gym lengkap untuk kebugaran tubuh."]
];

// Function untuk menampilkan fasilitas
function tampilkanFasilitas($data) {
    foreach ($data as $item) {
        echo "<div class='fasilitas-item'>";
        echo "<h3>{$item['nama']}</h3>";
        echo "<p>{$item['deskripsi']}</p>";
        echo "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Fasilitas Hotel</title>
</head>
<body>
    <h2>Fasilitas Kami</h2>
    <div class="fasilitas-container">
        <?php tampilkanFasilitas($fasilitas); ?>
    </div>
</body>
</html>
