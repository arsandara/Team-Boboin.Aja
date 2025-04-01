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
        </span>
      </div>
      <nav class="space-x-6">
        <a class="hover:text-gray-300" href="home.html">
          Home
        </a>
        <a class="hover:text-gray-300" href="rooms.html">
          Rooms
        </a>
        <a class="hover:text-gray-300" href="facilities.html">
          Facilities
        </a>
        <a class="hover:text-gray-300" href="contact.html">
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
      <div class="mt-6 bg-white text-black rounded-lg shadow-lg p-4 flex space-x-4">
        <div>
          <label class="block text-sm font-medium" for="checkin">
            Check In
          </label>
          <input class="mt-1 block w-full border-gray-300 rounded-md" id="checkin" type="date">
        </div>
        <div>
          <label class="block text-sm font-medium" for="checkout">
            Check Out
          </label>
          <input class="mt-1 block w-full border-gray-300 rounded-md" id="checkout" type="date">
        </div>
        <div>
          <label class="block text-sm font-medium" for="person">
            Person
          </label>
          <select class="mt-1 block w-full border-gray-300 rounded-md" id="person">
            <option>
              01 Person
            </option>
            <option>
              02 Person
            </option>
            <option>
              03 Person
            </option>
            <option>
              04 Person
            </option>
          </select>
        </div>

        <button class="bg-teal-900 text-white px-4 py-2 rounded-md">Available Room</button>
        </script>
      </div>
    </div>
  </section>

  <!-- Cabin Filters -->
  <div class="container mx-auto my-8 px-6">
    <div class="flex space-x-2 mb-6 overflow-x-auto">
      <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('all')">All</button>
      <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('family')">Family Cabin</button>
      <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('jacuzzi')">Jacuzzi Cabin</button>
      <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('pet')">Pet Friendly Cabin</button>
      <button class="bg-white border border-gray-300 px-4 py-2 rounded" onclick="filterCabins('romantic')">Romantic Cabin</button>
    </div>
    <script>
      function filterCabins(type) {
        // Ambil semua elemen cabin-card
        const cabins = document.querySelectorAll('.cabin-card');
    
        // Iterasi setiap cabin-card
        cabins.forEach(cabin => {
          if (type === 'all') {
            // Tampilkan semua kamar
            cabin.style.display = 'block';
          } else {
            // Hanya tampilkan kamar yang memiliki kelas kategori yang sesuai
            if (cabin.classList.contains(type)) {
              cabin.style.display = 'block';
            } else {
              cabin.style.display = 'none';
            }
          }
        });
    
        // Hanya pilih tombol filter (bukan tombol Book Now)
        const filterButtons = document.querySelectorAll('.container > .flex > button');
        
        // Reset semua tombol filter ke state default
        filterButtons.forEach(button => {
          button.classList.remove('bg-teal-900', 'text-white');
          button.classList.add('bg-white', 'border', 'border-gray-300');
        });
    
        // Set tombol filter yang aktif ke state hijau
        const activeFilterButton = document.querySelector(`.container > .flex > button[onclick="filterCabins('${type}')"]`);
        if (activeFilterButton) {
          activeFilterButton.classList.remove('bg-white', 'border-gray-300');
          activeFilterButton.classList.add('bg-teal-900', 'text-white');
        }
      }
    
      // Set tombol "All" sebagai aktif secara default saat halaman dimuat
      document.addEventListener('DOMContentLoaded', function() {
        const allButton = document.querySelector(`.container > .flex > button[onclick="filterCabins('all')"]`);
        if (allButton) {
          allButton.classList.remove('bg-white', 'border-gray-300');
          allButton.classList.add('bg-teal-900', 'text-white');
        }
      });
    </script>

  <!-- Explore Amazing Rooms -->
  <section class="container mx-auto py-12 px-6">
    <h2 class="text-2xl font-bold mb-6">
      Explore Amazing Rooms
    </h2>
    <div class="flex space-x-6 overflow-x-auto scrollbar-hide whitespace-nowrap">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#Deluxe"><img alt="Deluxe Cabin" class="w-full h-48 object-cover"
            src="Deluxe Cabin.jpeg"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Deluxe Cabin

          </h3>
          <div class="flex items-center text-sm text-gray-500 m-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            2 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block"></a>
        <a href="rooms.html#Executive"> <img alt="Executive Cabin" class="w-full h-48 object-cover"
            src="Executive Cabin.png">
          <div class="p-4">
            <h3 class="text-lg font-semibold">
              Executive Cabin
            </h3>
            <div class="flex items-center text-sm text-gray-500 mt-2">
              <i class="fas fa-star text-yellow-500 mr-1"></i>
              4.9
              <i class="fas fa-user-friends ml-4 mr-1"></i>
              2 peoples
            </div>
            <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
          </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#ExecutiveJacuzzi"><img alt="Executive Cabin with Jacuzzi"
            class="w-full h-48 object-cover" src="Executive Cabin with Jacuzzi.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Executive Cabin with Jacuzzi
          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            2 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#Family"> <img alt="Executive Cabin with Jacuzzi" class="w-full h-48 object-cover"
            src="Family Cabin.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Family Cabin

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            4 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#familyJacuzzi"><img alt="Family Cabin with Jacuzzi"
            class="w-full h-48 object-cover" src="Family Cabin with Jacuzzi.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Family Cabin with Jacuzzi

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            4 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#2Paws"><img alt="Executive Cabin with Jacuzzi" class="w-full h-48 object-cover"
            src="2 Paws Cabin.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            2 Paws Cabin

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            2 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#4Paws"><img alt="Executive Cabin with Jacuzzi" class="w-full h-48 object-cover"
            src="4 Paws Cabin.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            4 Paws Cabin

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            4 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#Romantic"><img alt="Executive Cabin with Jacuzzi" class="w-full h-48 object-cover" src="Romantic Cabin.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Romantic Cabin

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            2 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg overflow-hidden w-80 flex-shrink-0 inline-block">
        <a href="rooms.html#RoamnticJacuzzi"><img alt="Executive Cabin with Jacuzzi" class="w-full h-48 object-cover" src="Romantic Cabin with Jacuzzi.png"></a>
        <div class="p-4">
          <h3 class="text-lg font-semibold">
            Romantic Cabin with Jacuzzi

          </h3>
          <div class="flex items-center text-sm text-gray-500 mt-2">
            <i class="fas fa-star text-yellow-500 mr-1"></i>
            4.9
            <i class="fas fa-user-friends ml-4 mr-1"></i>
            2 peoples
          </div>
          <p class="mt-2 text-lg font-bold">Rp. 100.000.000</p>
        </div>
      </div>
  </section>

  <!-- Services & Facilities -->
  <section class="container mx-auto py-12 px-6">
    <h2 class="text-2xl font-bold mb-6">Services & Facilities</h2>
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

  <!-- WhatsApp, Social Media, and Map (Two Columns) -->
  <section class="container mx-auto py-12 px-6">
    <h2 class="text-xl font-bold mb-6">Contact Our Friendly Team</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- WhatsApp and Social Media -->
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
      <!-- Map Section -->
      <div>
        <iframe class="w-full h-full rounded-lg shadow-md" style="min-height: 400px;"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.203351538528!2d109.23085717476867!3d-7.550198674634177!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6559cb2b7a3e4f%3A0x301e8f1fc28ff20!2sBaturaden%2C%20Banyumas%20Regency%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1617045959045!5m2!1sen!2sid"
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
    <!-- Address Section -->
    <div class="bg-gray-100 p-6 rounded-lg shadow-md mt-6">
      <h3 class="text-lg font-semibold">Find Boboin.Aja</h3>
      <p class="text-gray-600"><strong>Address:</strong></p>
      <p class="text-gray-600 text-sm">
        Jl. Pancuran 7, Hamlet III Berubahan, Kemutug Lor, Baturaden District, Banyumas Regency, Central Java,
        Baturaden,
        Purwokerto, Indonesia, 53151
      </p>
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