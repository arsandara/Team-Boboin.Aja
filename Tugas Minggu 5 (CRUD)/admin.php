<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boboin.Aja - Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
  </head>
  <body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
      <!-- Sidebar -->
      <div class="bg-teal-900 text-white w-64 flex flex-col p-5 fixed h-full">
        <div class="flex items-center justify-center mb-6">
          <img src="Logo.png" alt="Boboin.Aja Logo" class="h-12" />
        </div>
        <nav class="space-y-3">
          <a
            href="admin.html"
            class="flex items-center py-3 px-4 rounded-lg bg-teal-700"
          >
            <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i>
            <span>Dashboard</span>
          </a>
          <a
            href="reservation.html"
            class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200"
          >
            <i class="fas fa-calendar-alt w-6 text-center mr-3"></i>
            <span>Reservations</span>
          </a>
          <a
            href="adRooms.html"
            class="flex items-center py-3 px-4 rounded-lg hover:bg-teal-700 transition duration-200"
          >
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
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <div id="currentDate">Today: 10 March 2025</div>
          </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
          <!-- Stats -->
          <div class="grid grid-cols-4 gap-4">
            <div
              class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"
            >
              <i class="fas fa-user-friends text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">TODAY'S GUEST</p>
                <h2 class="text-2xl font-bold">24</h2>
              </div>
            </div>
            <div
              class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"
            >
              <i class="fas fa-calendar-plus text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">NEW BOOKINGS</p>
                <h2 class="text-2xl font-bold">4</h2>
              </div>
            </div>
            <div
              class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"
            >
              <i class="fas fa-hotel text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">ROOM AVAILABILITY</p>
                <h2 class="text-2xl font-bold">78%</h2>
              </div>
            </div>
            <div
              class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"
            >
              <i class="fas fa-dollar-sign text-2xl text-gray-500"></i>
              <div>
                <p class="text-gray-500">REVENUE (TODAY)</p>
                <h2 class="text-2xl font-bold">12</h2>
              </div>
            </div>
          </div>

          <!-- Recent Bookings & Room Occupancy -->
          <div class="grid grid-cols-3 gap-6 mt-6">
            <div class="col-span-2 bg-white p-6 rounded-lg shadow">
              <h2 class="text-lg font-semibold mb-4">Recent Bookings</h2>
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr>
                    <th class="p-2 border-b">Guest</th>
                    <th class="p-2 border-b">Room</th>
                    <th class="p-2 border-b">Check-in</th>
                    <th class="p-2 border-b">Status</th>
                    <th class="p-2 border-b">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="p-2 border-b">Qutbh Habiburrahman</td>
                    <td class="p-2 border-b">Romantic Cabin</td>
                    <td class="p-2 border-b">Mar 10, 2025</td>
                    <td class="p-2 border-b text-green-600">Checked In</td>
                    <td class="p-2 border-b">
                      <a
                        href="delete.php"
                        onclick="return confirm('Are you sure to delete this reservation?');"
                      >
                        <button class="bg-red-500 text-white px-3 py-2 rounded">
                          Delete
                        </button>
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td class="p-2 border-b">Ara Arsanda</td>
                    <td class="p-2 border-b">2 Paws Cabin</td>
                    <td class="p-2 border-b">Mar 11, 2025</td>
                    <td class="p-2 border-b text-yellow-600">Reserved</td>
                    <td class="p-2 border-b">
                      <a
                        href="delete.php"
                        onclick="return confirm('Are you sure to delete this reservation?');"
                      >
                        <button class="bg-red-500 text-white px-3 py-2 rounded">
                          Delete
                        </button>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
              <h2 class="text-lg font-semibold mb-4">
                <i class="fas fa-bed"></i> Room Occupancy
              </h2>
              <ul>
                <li class="text-red-600">Deluxe Cabin - 18/20 rooms</li>
                <li class="text-green-600">Executive Cabin - 2/20 rooms</li>
                <li class="text-green-600">
                  Executive Cabin with Jacuzzi - 1/8 rooms
                </li>
                <li class="text-green-600">Family Cabin - 1/5 rooms</li>
                <li class="text-green-600">
                  Family Cabin with Jacuzzi - 1/7 rooms
                </li>
                <li class="text-green-600">2 Paws Cabin - 2/7 rooms</li>
                <li class="text-red-600 font-bold">4 Paws Cabin - SOLD OUT</li>
                <li class="text-red-600">Romantic Cabin - 9/10 rooms</li>
                <li class="text-green-600">
                  Romantic Cabin with Jacuzzi - 1/7 rooms
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      const today = new Date();
      const options = { day: "numeric", month: "long", year: "numeric" };
      document.getElementById(
        "currentDate"
      ).textContent = `Today: ${today.toLocaleDateString("en-US", options)}`;
    </script>
  </body>
</html>
