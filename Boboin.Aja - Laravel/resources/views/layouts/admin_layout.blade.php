<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin - Boboin.Aja')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gray-50 font-sans">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-teal-900 text-white w-64 flex flex-col p-5 fixed h-full">
      <div class="flex items-center justify-center mb-6">
        <img src="{{ asset('images/Logo.png') }}" alt="Boboin.Aja Logo" class="h-12" />
      </div>
      <nav class="space-y-3">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 rounded-lg {{ request()->is('admin/dashboard') ? 'bg-teal-700' : 'hover:bg-teal-700' }}">
          <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('admin.reservations.index') }}" class="flex items-center py-3 px-4 rounded-lg {{ request()->is('admin/reservations*') ? 'bg-teal-700' : 'hover:bg-teal-700' }}">
          <i class="fas fa-calendar-alt w-6 text-center mr-3"></i><span>Reservations</span>
        </a>
        <a href="{{ route('admin.rooms') }}" class="flex items-center py-3 px-4 rounded-lg {{ request()->is('admin/rooms*') ? 'bg-teal-700' : 'hover:bg-teal-700' }}">
          <i class="fas fa-bed w-6 text-center mr-3"></i><span>Rooms</span>
        </a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1">
      <div class="bg-teal-800 text-white p-6">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold">@yield('title')</h1>
          <div class="flex items-center space-x-4">
            <div class="text-sm">Today: {{ now()->format('d F Y') }}</div>
            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="flex items-center space-x-2 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
              </button>
            </form>
            @else
            <button id="openPopup" class="bg-white text-teal-900 px-4 py-2 rounded hover:bg-gray-200">
              Login / Sign Up
            </button>
            @endauth
          </div>
        </div>
      </div>

      <div class="p-6">
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>