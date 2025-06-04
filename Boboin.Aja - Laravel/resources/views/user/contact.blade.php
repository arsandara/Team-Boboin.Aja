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
          <a href="https://wa.me/+6285175389380" class="mt-2 text-blue-600 underline">wa.me/+6285175389380</a>
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

@endsection

@section('scripts')
    <script src="{{ asset('js/dateSync.js') }}"></script>
@endsection