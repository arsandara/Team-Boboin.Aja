<?php
require 'connection.php';

function formatRupiah($number) {
    return 'Rp. ' . number_format($number, 0, ',', '.');
}

$filter = $_GET['filter'] ?? 'all';
$rooms = [];

try {
    $sql = "SELECT * FROM rooms WHERE 1=1";
    
    if ($filter !== 'all') {
      if ($filter === 'jacuzzi') {
          $sql .= " AND has_jacuzzi = TRUE";
      } else {
          // For family/romantic, include their jacuzzi versions too
          if ($filter === 'family' || $filter === 'romantic') {
              $sql .= " AND (room_type = '" . $conn->real_escape_string($filter) . "'";
              $sql .= " OR (name LIKE '%" . $conn->real_escape_string($filter) . "%' AND name LIKE '%Jacuzzi%'))";
          } else {
              $sql .= " AND room_type = '" . $conn->real_escape_string($filter) . "'";
          }
      }
  }
    
    $sql .= " ORDER BY room_id ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Boboin.Aja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <header class="bg-teal-900 text-white">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="flex items-center">
        <img alt="Boboin.Aja logo" class="h-10 mr-3" height="50" src="Logo.png" width="100">
      </div>
      <nav class="space-x-6">
      <a class="hover:text-gray-300" href="home.php">
          Home
        </a>
        <a class="hover:text-gray-300" href="rooms.php">
          Rooms
        </a>
        <a class="hover:text-gray-300" href="facilities.php">
          Facilities
        </a>
        <a class="hover:text-gray-300" href="contact.php">
          Contact
        </a>
      </nav>

      <!-- Button -->
      <button id="openPopup" class="bg-white text-teal-900 px-4 py-2 rounded hover:bg-gray-200">Login / Sign Up</button>
    </div>
  </header>
  <!-- Pop-up -->
  <div id="popupContainer" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="popup-content">
      <button id="closePopup" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
      <div class="flex flex-col items-center mb-6">
        <img src="Logogreen.png" alt="Boboin.Aja logo" class="w-40 h-auto mb-4">
        <p class="text-center text-gray-600 text-sm">
          Find your perfect stay, where modern comfort meets serene tranquility.
        </p>
      </div>
      <div class="flex justify-center mb-4 border-b">
        <button class="tab-button active px-4 py-2" onclick="showTab('signin')">Log In</button>
        <button class="tab-button px-4 py-2" onclick="showTab('signup')">Sign Up</button>
      </div>
      <div id="signin" class="tab-content active">
        <input type="text" placeholder="Email or username" class="w-full p-2 border rounded mb-3">
        <input type="password" placeholder="Password" class="w-full p-2 border rounded mb-3">
        <button class="w-full bg-teal-900 text-white py-2 rounded hover:bg-teal-800">Log In</button>
        <p class="text-center mt-4 text-sm">
          Don't have an account? <a href="#" onclick="showTab('signup')" class="text-green-500 hover:underline">Sign
            Up</a>
        </p>
      </div>
      <div id="signup" class="tab-content hidden">
        <input type="text" placeholder="Full Name" class="w-full p-2 border rounded mb-3">
        <input type="email" placeholder="Email" class="w-full p-2 border rounded mb-3">
        <input type="password" placeholder="Password" class="w-full p-2 border rounded mb-3">
        <button class="w-full bg-teal-900 text-white py-2 rounded hover:bg-teal-800">Sign Up</button>
        <p class="text-center mt-4 text-sm">
          Already have an account? <a href="#" onclick="showTab('signin')" class="text-green-500 hover:underline">Log
            In</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    function openPopup(tab = "signin") {
      const popupContainer = document.getElementById("popupContainer");
      popupContainer.classList.remove("hidden");
      popupContainer.classList.add("flex");
      showTab(tab);
    }

    function closePopup() {
      const popupContainer = document.getElementById("popupContainer");
      popupContainer.classList.remove("flex");
      popupContainer.classList.add("hidden");
    }

    document.getElementById("openPopup").addEventListener("click", function () {
      openPopup("signin");
    });

    document.getElementById("closePopup").addEventListener("click", closePopup);

    window.addEventListener("click", function (event) {
      const popupContainer = document.getElementById("popupContainer");
      if (event.target === popupContainer) {
        closePopup();
      }
    });

    function showTab(tabId) {
      document.querySelectorAll(".tab-button").forEach(btn => {
        btn.classList.remove("active", "border-b-2", "border-teal-900", "text-teal-900");
      });
      document.querySelectorAll(".tab-content").forEach(tab => {
        tab.classList.remove("active");
        tab.classList.add("hidden");
      });
      const activeButton = document.querySelector(`[onclick="showTab('${tabId}')"]`);
      activeButton.classList.add("active", "border-b-2", "border-teal-900", "text-teal-900");
      const activeContent = document.getElementById(tabId);
      activeContent.classList.remove("hidden");
      activeContent.classList.add("active");
    }
  </script>

  <!-- Hero Section -->
  <section class="relative">
    <img class="w-full h-96 object-cover" height="600" src="HEADER.png" width="1920">
    <div
      class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center text-white px-4">
      <h1 class="text-4xl font-bold">
        More Than a Stay,
        <br>
        It’s Where You Find Peace
      </h1>
      <p class="mt-4">
        Find your perfect stay, where modern comfort meets serene tranquility.
        <br>
        Recharge, unwind, and experience peace like never before.
      </p>
      <!-- Booking Form - Sama di semua halaman -->
      <div class="mt-6 bg-white text-black rounded-lg shadow-lg p-4">
          <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
              <!-- Check In -->
              <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-right md:text-left" for="checkin">
                      Check In
                  </label>
                  <input class="mt-1 block w-full border-gray-300 rounded-md" id="checkin" type="date" required>
              </div>

              <!-- Check Out -->
              <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-right md:text-left" for="checkout">
                      Check Out
                  </label>
                  <input class="mt-1 block w-full border-gray-300 rounded-md" id="checkout" type="date" required>
              </div>
              
              <!-- Person -->
              <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-right md:text-left" for="person">
                      Person
                  </label>
                  <select class="mt-1 block w-full border-gray-300 rounded-md" id="person" required>
                      <option value="01 Person">01 Person</option>
                      <option value="02 Person" selected>02 Person</option>
                      <option value="03 Person">03 Person</option>
                      <option value="04 Person">04 Person</option>
                  </select>
              </div>
              
              <!-- Available Room Button -->
              <div class="w-full md:w-auto text-center md:text-right mt-4 md:mt-0">
                  <a href="rooms.php" class="inline-block bg-teal-900 text-white px-6 py-2 rounded-md">
                      Available Room
                    </a>
                  </script>
                </div>
              </div>
            </section>

  <!-- Cabin Filters -->
  <div class="container mx-auto my-8 px-6">
    <div class="flex space-x-2 mb-6 overflow-x-auto">
        <button class="bg-teal-900 text-white px-4 py-2 rounded" onclick="filterCabins('all')">All</button>
        <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('family')">Family</button>
        <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('pet_friendly')">Pet Friendly</button>
        <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('romantic')">Romantic</button>
    </div>
    <script>
    function filterCabins(type) {
          const cabins = document.querySelectorAll('.cabin-card');
        
        cabins.forEach(cabin => {
            const roomType = cabin.getAttribute('data-room-type');
            if (type === 'all') {
                cabin.style.display = 'block';
            } else {
                cabin.style.display = (roomType === type) ? 'block' : 'none';
            }
        });

        // Update tombol aktif
        const filterButtons = document.querySelectorAll('.container > .flex > button');
        filterButtons.forEach(button => {
            const filterType = button.getAttribute('onclick').replace("filterCabins('", "").replace("')", "");
            if (filterType === type) {
                button.classList.remove('bg-white', 'border-gray-300');
                button.classList.add('bg-teal-900', 'text-white');
            } else {
                button.classList.remove('bg-teal-900', 'text-white');
                button.classList.add('bg-white', 'border', 'border-gray-300');
            }
        });
    }

    // Set default filter 'All' saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        filterCabins('all');
    });
    </script>

    <!-- Cabin Listings -->
    <div class="space-y-6">
        <?php foreach ($rooms as $room): ?>
        <div class="cabin-card bg-white rounded-lg shadow-md overflow-hidden" 
          data-room-type="<?php echo htmlspecialchars($room['room_type']); ?>">
            <div id="<?php echo htmlspecialchars(str_replace(' ', '', $room['room_name'])); ?>" class="relative">
                <img src="<?php echo htmlspecialchars($room['image_booking']); ?>" 
                    alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="w-full h-80 object-cover">
                
                <!-- Tags berdasarkan tipe kamar -->
                <div class="absolute top-4 right-4 flex space-x-2">
                    <?php if ($room['room_type'] === 'Standard'): ?>
                        <span class="tag-pill tag-standard bg-red-500 text-white px-3 py-1 rounded-full text-sm">Standard</span>
                    <?php elseif ($room['room_type'] === 'Family'): ?>
                        <span class="tag-pill tag-family bg-green-500 text-white px-3 py-1 rounded-full text-sm">Family</span>
                    <?php elseif ($room['room_type'] === 'Pet Friendly'): ?>
                        <span class="tag-pill tag-pet bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">Pet Friendly</span>
                    <?php elseif ($room['room_type'] === 'Romantic'): ?>
                        <span class="tag-pill tag-romantic bg-pink-500 text-white px-3 py-1 rounded-full text-sm">Romantic</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($room['room_name']); ?></h3>
                
                <div class="flex items-center mt-3 flex-wrap gap-4">
                    <div class="flex items-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <span class="ml-1 text-gray-800 font-medium"><?php echo htmlspecialchars($room['rating'] ?? '4.8'); ?></span>
                    </div>
                    
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-user mr-1"></i>
                        <span><?php echo htmlspecialchars($room['capacity']); ?> people</span>
                    </div>
                    
                    <?php if ($room['breakfast_included']): ?>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-coffee mr-1"></i>
                        <span>Breakfast</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($room['room_type'] === 'Pet Friendly'): ?>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-paw mr-1"></i>
                        <span><?php echo $room['capacity'] > 2 ? 'All pets' : 'Small pets'; ?></span>
                    </div>
                    <?php elseif ($room['room_type'] === 'Romantic'): ?>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-heart mr-1"></i>
                        <span><?php echo $room['has_jacuzzi'] ? 'Luxury Sweet' : 'Couple Special'; ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-6 flex justify-between items-center">
                    <p class="text-2xl font-bold text-gray-900"><?php echo formatRupiah($room['price']); ?></p>
                    
                    <form action="booking.php" method="GET">
                        <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                        <button type="submit" class="book-now-btn text-sm bg-teal-900 text-white px-3 py-1 rounded">
                            Book Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

        <script>
          document.addEventListener("DOMContentLoaded", function () {
            if (window.location.hash) {
              let element = document.querySelector(window.location.hash);
              if (element) {
                element.scrollIntoView({ behavior: "smooth" });
              }
            }
          });

        </script>

        <!-- WhatsApp, Social Media, and Map (Two Columns) -->
        <div class="mt-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="flex flex-col space-y-6">
            <!-- WhatsApp -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md flex flex-col items-center text-center">
              <div class="bg-white p-4 rounded-full shadow-lg">
                <i class="fab fa-whatsapp text-4xl text-green-500"></i>
              </div>
              <h3 class="mt-4 text-lg font-semibold">Chat to Admin</h3>
              <p class="text-gray-600 text-sm">Speak to our friendly team.</p>
              <a href="https://wa.me/081234567891" class="mt-2 text-blue-600 underline">wa.me/081234567891</a>
            </div>

            <!-- Social Media -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md text-center">
              <h3 class="text-lg font-semibold">Social Media</h3>
              <p class="text-gray-600 text-sm">Get to know us more.</p>
              <div class="flex justify-center space-x-4 mt-4">
                <a href="#" class="text-black text-2xl"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-black text-2xl"><i class="fab fa-x"></i></a>
                <a href="#" class="text-black text-2xl"><i class="fab fa-tiktok"></i></a>
              </div>
            </div>
          </div>

          <!-- Google Map -->
          <div>
            <iframe class="w-full h-full rounded-lg shadow-md"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.203351538528!2d109.23085717476867!3d-7.550198674634177!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6559cb2b7a3e4f%3A0x301e8f1fc28ff20!2sBaturaden%2C%20Banyumas%20Regency%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1617045959045!5m2!1sen!2sid"
              allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </div>

        <!-- Address Section -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-semibold">Find Boboin.Aja</h3>
          <p class="text-gray-600"><strong>Address:</strong></p>
          <p class="text-gray-600 text-sm">Jl. Pancuran 7, Hamlet III Berubahan, Kemutug Lor, Baturaden District,
            Banyumas
            Regency, Central Java, Baturaden, Purwokerto, Indonesia, 53151</p>
        </div>
        </section>
        </div>

        <!-- Footer  -->
        </div>
        <footer class="bg-teal-900 text-white py-12">
          <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
              <!-- Logo Column -->
              <div class="flex justify-center">
                <div class="w-48">
                  <img src="Logo.png" alt="Boboin.Aja logo" class="w-full h-auto" />
                </div>
              </div>

              <!-- About Column 1 -->
              <div>
                <h3 class="text-lg font-semibold mb-4">About Boboin.Aja</h3>
                <p class="text-gray-300 text-justify">
                  Our hotel is designed for those who seek comfort, relaxation, and a deep connection with nature. With
                  cozy
                  and well-appointed rooms, modern facilities, and breathtaking views of lush greenery, we provide a
                  serene
                  escape from the noise and stress of daily life.
                </p>
              </div>

              <!-- About Column 2 -->
              <div>
                <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                <p class="text-gray-300 text-justify">
                  As part of our commitment to sustainability and guest well-being, we proudly maintain a 100%
                  smoke-free
                  environment. We believe in preserving the purity of the air, allowing guests to fully enjoy the fresh,
                  unpolluted atmosphere that nature provides.
                </p>
              </div>

              <!-- About Column 3 -->
              <div>
                <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                <p class="text-gray-300 text-justify">
                  Surrounded by nature and designed for relaxation, our hotel is the perfect place to unwind, recharge,
                  and
                  embrace the beauty of the outdoors.
                </p>
              </div>
            </div>

            <!-- Copyright with top border -->
            <div class="text-center mt-10 pt-4 border-t border-teal-800">
              <p class="text-gray-400">
                © Copyright Boboin.Aja, All right reserved.
              </p>
            </div>
          </div>
        </footer>

    <script>
    // Set tanggal hari ini dan besok
    const today = new Date();
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);

    // Format ke yyyy-mm-dd
    const formatDate = (date) => {
      return date.toISOString().split('T')[0];
    };

    document.getElementById('checkin').value = formatDate(today);
    document.getElementById('checkout').value = formatDate(tomorrow);
    document.getElementById('persons').value = 2;

    // Arahkan tombol ke halaman room.html
    document.getElementById('availableBtn').addEventListener('click', () => {
      window.location.href = "rooms.php";
    });
  </script>
<script src="dateSync.js"></script>
</body>
</html>