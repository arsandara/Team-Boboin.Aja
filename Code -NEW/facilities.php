<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Boboin.Aja</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body class="bg-gray-100">
  <header class="bg-teal-900 text-white">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="flex items-center">
        <img alt="Boboin.Aja logo" class="h-10 mr-3" height="50" src="Logo.png" width="100">
        </span>
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

  <!-- Services & Facilities -->
  <section class="container mx-auto py-12 px-6">
    <h2 class="text-2xl font-bold mb-6">
      Services & Facilities
    </h2>
    <div class="grid grid-cols-3 gap-4">
      <div class="relative-container row-span-2">
        <img alt="Cozy Rooms" class="w-full h-full object-cover" src="Cozy Rooms.png">
        <div class="overlay">Cozy Rooms</div>
      </div>
      <div class="relative-container">
        <img alt="Private Jacuzzi" class="w-full h-full object-cover" src="Private Jacuzzi.png">
        <div class="overlay">Private Jacuzzi</div>
      </div>
      <div class="relative-container">
        <img alt="Dog Park" class="w-full h-full object-cover" src="Dog Park.png">
        <div class="overlay">Dog Park</div>
      </div>
      <div class="relative-container">
        <img alt="Outdoor Lounge" class="w-full h-full object-cover" src="Outdoor Louge.png">
        <div class="overlay">Outdoor Lounge</div>
      </div>
      <div class="relative-container">
        <img alt="Dining & Bar" class="w-full h-full object-cover" src="Dining and Bar.png">
        <div class="overlay">Dining & Bar</div>
      </div>
    </div>
</section>

<!-- Footer  -->
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
            Our hotel is designed for those who seek comfort, relaxation, and a deep connection with nature. With cozy
            and well-appointed rooms, modern facilities, and breathtaking views of lush greenery, we provide a serene
            escape from the noise and stress of daily life.
          </p>
        </div>

        <!-- About Column 2 -->
        <div>
          <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
          <p class="text-gray-300 text-justify">
            As part of our commitment to sustainability and guest well-being, we proudly maintain a 100% smoke-free
            environment. We believe in preserving the purity of the air, allowing guests to fully enjoy the fresh,
            unpolluted atmosphere that nature provides.
          </p>
        </div>

        <!-- About Column 3 -->
        <div>
          <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
          <p class="text-gray-300 text-justify">
            Surrounded by nature and designed for relaxation, our hotel is the perfect place to unwind, recharge, and
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