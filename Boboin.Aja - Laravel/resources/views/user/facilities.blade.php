@extends('layouts.layout')

@section('content')
  <!-- Hero Section -->
<section class="relative">
    <img class="w-full h-96 object-cover" height="600" <img src="{{ asset('images/HEADER.png') }}" width="1920">
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
              <a href="{{ url('/rooms') }}" class="inline-block bg-teal-900 text-white px-6 py-2 rounded-md">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative-container row-span-2">
        <img alt="Cozy Rooms" class="w-full h-full object-cover" src="{{ asset('images/Cozy Rooms.png') }}">
        <div class="overlay">Cozy Rooms</div>
        </div>
        <div class="relative-container">
        <img alt="Private Jacuzzi" class="w-full h-full object-cover" src="{{ asset('images/Private Jacuzzi.png') }}">
        <div class="overlay">Private Jacuzzi</div>
        </div>
        <div class="relative-container">
        <img alt="Dog Park" class="w-full h-full object-cover" src="{{ asset('images/Dog Park.png') }}">
        <div class="overlay">Dog Park</div>
        </div>
        <div class="relative-container">
        <img alt="Outdoor Lounge" class="w-full h-full object-cover" src="{{ asset('images/Outdoor Lounge.png') }}">
        <div class="overlay">Outdoor Lounge</div>
        </div>
        <div class="relative-container">
        <img alt="Dining & Bar" class="w-full h-full object-cover" src="{{ asset('images/Dining and Bar.png') }}">
        <div class="overlay">Dining & Bar</div>
        </div>
    </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/dateSync.js') }}"></script>
@endsection
