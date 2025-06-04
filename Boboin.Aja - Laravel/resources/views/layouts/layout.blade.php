<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Boboin.Aja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-poppins">

    <!-- Header/Navbar -->
    <header class="bg-teal-900 text-white">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo Boboin.Aja" class="h-10 mr-3">
            </div>

            <!-- Desktop Nav (Menampilkan Menu Navigasi Utama di Desktop) -->
            <nav class="hidden md:flex items-center space-x-8">
                <a class="hover:text-gray-300" href="{{ url('/') }}">Home</a>
                <a class="hover:text-gray-300" href="{{ url('/rooms') }}">Rooms</a>
                <a class="hover:text-gray-300" href="{{ url('/facilities') }}">Facilities</a>
                <a class="hover:text-gray-300" href="{{ url('/contact') }}">Contact</a>
            </nav>

            <!-- Right Side (Profile + Hamburger Menu) -->
            <div class="flex items-center space-x-4">
                <!-- Profile Button -->
                @auth
                <div class="relative">
                    <button id="profileMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('default-profile.png') }}" alt="Profile" class="w-9 h-9 rounded-full border border-white shadow">
                    </button>
                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-50">
                        <ul class="py-2 text-sm text-gray-800">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                        Logout
                                        <i class="fas fa-sign-out-alt text-red-500 ml-2"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @else
                <button id="openPopup" class="bg-white text-teal-900 px-4 py-2 rounded hover:bg-gray-200">
                    Login / Sign Up
                </button>
                @endauth

                <!-- Hamburger Icon (Mobile Only) -->
                <button id="menu-toggle" class="md:hidden text-2xl focus:outline-none ml-4">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Popup -->
    <div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-60 z-40 hidden justify-center items-start pt-24">
        <div class="bg-white text-teal-900 w-11/12 max-w-xs rounded-lg p-6 shadow-lg relative">
            <button id="close-mobile-menu" class="absolute top-4 right-4 text-2xl text-gray-500 hover:text-gray-700">
                &times;
            </button>
            <div class="flex flex-col space-y-4 text-center text-lg font-medium mt-6">
                <a href="{{ url('/') }}" class="hover:text-teal-700">Home</a>
                <a href="{{ url('/rooms') }}" class="hover:text-teal-700">Rooms</a>
                <a href="{{ url('/facilities') }}" class="hover:text-teal-700">Facilities</a>
                <a href="{{ url('/contact') }}" class="hover:text-teal-700">Contact</a>
            </div>
        </div>
    </div>

    <!-- Popup Login/Register -->
    <div id="popupContainer" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="popup-content bg-white p-8 rounded shadow-lg relative w-full max-w-md">
            <button id="closePopup" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/Logogreen.png') }}" alt="Boboin.Aja logo" class="w-40 h-auto mb-4">
                <p class="text-center text-gray-600 text-sm">Find your perfect stay, where modern comfort meets serene tranquility.</p>
            </div>
            <div class="flex justify-center mb-4 border-b">
                <button class="tab-button active px-4 py-2 border-b-2 border-teal-900 text-teal-900" onclick="showTab('signin')">Log In</button>
                <button class="tab-button px-4 py-2" onclick="showTab('signup')">Sign Up</button>
            </div>
            @include('auth.login')
            @include('auth.register')
        </div>
    </div>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-teal-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <div class="flex justify-center">
                    <div class="w-48">
                        <img src="{{ asset('images/Logo.png') }}" alt="Logo Boboin.Aja" class="w-full h-auto" />
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Boboin.Aja</h3>
                    <p class="text-gray-300 text-justify">
                        Our hotel is designed for those who seek comfort, relaxation, and a deep connection with nature. With cozy
                        and well-appointed rooms, modern facilities, and breathtaking views of lush greenery, we provide a serene
                        escape from the noise and stress of daily life.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                    <p class="text-gray-300 text-justify">
                        As part of our commitment to sustainability and guest well-being, we proudly maintain a 100% smoke-free
                        environment. We believe in preserving the purity of the air, allowing guests to fully enjoy the fresh,
                        unpolluted atmosphere that nature provides.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-0">Invisible Heading</h3>
                    <p class="text-gray-300 text-justify">
                        Surrounded by nature and designed for relaxation, our hotel is the perfect place to unwind, recharge, and
                        embrace the beauty of the outdoors.
                    </p>
                </div>
            </div>
            <div class="text-center mt-10 pt-4 border-t border-teal-800">
                <p class="text-gray-400">© Copyright Boboin.Aja, All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Pastikan DOM sudah loaded sebelum menjalankan script
        document.addEventListener('DOMContentLoaded', function() {
            
            // Profile dropdown toggle
            const profileMenuButton = document.getElementById("profileMenuButton");
            const profileMenu = document.getElementById("profileMenu");
            
            if (profileMenuButton && profileMenu) {
                profileMenuButton.addEventListener("click", function(e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle("hidden");
                });
            }

            // Close profile menu when clicking outside
            window.addEventListener("click", function(event) {
                if (profileMenu && profileMenuButton && 
                    !profileMenuButton.contains(event.target) && 
                    !profileMenu.contains(event.target)) {
                    profileMenu.classList.add("hidden");
                }
            });

            // Hamburger menu toggle for mobile
            const menuToggle = document.getElementById("menu-toggle");
            const mobileMenu = document.getElementById("mobile-menu");
            const closeMobileMenu = document.getElementById("close-mobile-menu");

            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener("click", function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.remove("hidden");
                    mobileMenu.classList.add("flex");
                });
            }

            if (closeMobileMenu && mobileMenu) {
                closeMobileMenu.addEventListener("click", function() {
                    mobileMenu.classList.add("hidden");
                    mobileMenu.classList.remove("flex");
                });
            }

            // Close mobile menu when clicking outside
            if (mobileMenu) {
                mobileMenu.addEventListener("click", function(e) {
                    if (e.target === mobileMenu) {
                        mobileMenu.classList.add("hidden");
                        mobileMenu.classList.remove("flex");
                    }
                });
            }

            // Popup login/register functionality
            const openPopup = document.getElementById("openPopup");
            const closePopup = document.getElementById("closePopup");
            const popupContainer = document.getElementById("popupContainer");

            if (openPopup && popupContainer) {
                openPopup.addEventListener("click", function() {
                    popupContainer.classList.remove("hidden");
                    popupContainer.classList.add("flex");
                    showTab('signin'); // Default to signin tab
                });
            }

            if (closePopup && popupContainer) {
                closePopup.addEventListener("click", function() {
                    popupContainer.classList.remove("flex");
                    popupContainer.classList.add("hidden");
                });
            }

            // Close popup when clicking outside
            if (popupContainer) {
                popupContainer.addEventListener("click", function(event) {
                    if (event.target === popupContainer) {
                        popupContainer.classList.remove("flex");
                        popupContainer.classList.add("hidden");
                    }
                });
            }
        });

        // Tab switching function untuk login/register
        function showTab(tabId) {
            // Remove active classes from all buttons
            document.querySelectorAll(".tab-button").forEach(btn => {
                btn.classList.remove("active", "border-b-2", "border-teal-900", "text-teal-900");
            });
            
            // Hide all tab contents
            document.querySelectorAll(".tab-content").forEach(tab => {
                tab.classList.add("hidden");
                tab.classList.remove("active");
            });
            
            // Show active tab
            const activeButton = document.querySelector(`[onclick="showTab('${tabId}')"]`);
            const activeContent = document.getElementById(tabId);
            
            if (activeButton) {
                activeButton.classList.add("active", "border-b-2", "border-teal-900", "text-teal-900");
            }
            
            if (activeContent) {
                activeContent.classList.remove("hidden");
                activeContent.classList.add("active");
            }
        }

        // Fungsi untuk memastikan date formatting tetap konsisten
        // Jika ada custom date handling, pastikan locale diset dengan benar
        if (typeof Intl !== 'undefined') {
            // Set default locale untuk date formatting
            const dateOptions = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            
            // Helper function untuk format date konsisten
            window.formatDateConsistent = function(date) {
                if (date instanceof Date) {
                    return date.toLocaleDateString('id-ID', dateOptions);
                }
                return date;
            };
        }
    </script>
    
    @yield('scripts')
</body>
</html>