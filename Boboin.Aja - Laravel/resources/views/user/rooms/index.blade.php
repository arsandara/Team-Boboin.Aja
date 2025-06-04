@extends('layouts.layout')

@section('content')
<!-- Hero Section -->
<section class="relative">
    <img class="w-full h-96 object-cover" src="{{ asset('images/HEADER.png') }}" alt="Hero">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center text-white px-4">
        <h1 class="text-4xl font-bold">More Than a Stay,<br>It's Where You Find Peace</h1>
        <p class="mt-4">
            Find your perfect stay, where modern comfort meets serene tranquility.<br>
            Recharge, unwind, and experience peace like never before.
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
              <a href="{{ url('/rooms') }}" class="inline-block bg-teal-900 text-white px-6 py-2 rounded-md">
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

    <div class="space-y-6">
        @foreach ($rooms as $room)
        <div class="cabin-card bg-white rounded-lg shadow-md overflow-hidden mb-8" data-room-type="{{ $room->room_type }}">
            <div class="relative">
                <img src="{{ asset('images/' . $room->image_booking) }}" alt="{{ $room->room_name }}" class="w-full h-96 object-cover">
                <div class="absolute top-4 right-4 flex space-x-2">
                    @if ($room->room_type === 'Standard')
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">Standard</span>
                    @elseif ($room->room_type === 'Family')
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">Family</span>
                    @elseif ($room->room_type === 'Pet Friendly')
                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">Pet Friendly</span>
                    @elseif ($room->room_type === 'Romantic')
                        <span class="bg-pink-500 text-white px-3 py-1 rounded-full text-sm">Romantic</span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900">{{ $room->room_name }}</h3>
                <div class="flex items-center mt-3 flex-wrap gap-4">
                    <div class="flex items-center text-yellow-400">
                        <i class="fas fa-star"></i>
                        <span class="ml-1 text-gray-800 font-medium">{{ $room->rating ?? '4.8' }}</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-user mr-1"></i>
                        <span>{{ $room->capacity }} people</span>
                    </div>
                    @if ($room->breakfast_included)
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-coffee mr-1"></i>
                        <span>Breakfast</span>
                    </div>
                    @endif
                </div>
                <div class="mt-6 flex justify-between items-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($room->price, 0, ',', '.') }}</p>
                    <a href="{{ route('booking.create', $room->room_id) }}" class="text-sm bg-teal-900 text-white px-3 py-2 rounded hover:bg-teal-800">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/dateSync.js') }}"></script>
<script>
    function filterCabins(type) {
        const cabins = document.querySelectorAll('.cabin-card');
        cabins.forEach(cabin => {
            const roomType = cabin.getAttribute('data-room-type');
            cabin.style.display = (type === 'all' || roomType.toLowerCase() === type.toLowerCase()) ? 'block' : 'none';
        });

        const buttons = document.querySelectorAll('.container > .flex > button');
        buttons.forEach(button => {
            const btnType = button.getAttribute('onclick').replace("filterCabins('", "").replace("')", "");
            button.classList.toggle('bg-teal-900', btnType === type);
            button.classList.toggle('text-white', btnType === type);
            button.classList.toggle('bg-white', btnType !== type);
            button.classList.toggle('border', btnType !== type);
            button.classList.toggle('border-gray-300', btnType !== type);
        });
    }
</script>
@endsection
