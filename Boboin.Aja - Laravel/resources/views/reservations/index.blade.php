@extends('layouts.layout')

@section('content')
<div class="ml-64 flex-1">
    <div class="bg-teal-800 text-white p-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Reservation</h1>
        <div>Today: {{ date('F j, Y') }}</div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reservation.index') }}" class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                   placeholder="Search guest name...">
        </form>
    </div>

    <!-- Reservation Cards -->
    <div class="p-6 space-y-6">
        @foreach($reservations as $reservation)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-semibold">Booking Information</h2>
                    <div class="flex items-center mt-1 text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="font-medium">Booking Confirmed</span>
                    </div>
                    <p class="text-gray-500 mt-1">{{ $reservation->created_at->format('F d, Y') }}</p>
                </div>
                <div class="text-right text-gray-600 font-medium">
                    Booking ID: MR-{{ strtoupper(substr($reservation->room_name, 0, 2)) . str_pad($reservation->id, 3, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-medium text-gray-700 mb-2">Guest</h3>
                <p class="font-semibold">{{ $reservation->guest_name }}</p>
                <p class="text-gray-600">{{ $reservation->guest_email }}</p>
            </div>

            <!-- Table dan Price Summary sama seperti kode sebelumnya -->
            
            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.reservation.edit', $reservation->id) }}">
                    <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded font-medium transition duration-200">
                        Edit
                    </button>
                </a>
                <form action="{{ route('admin.reservation.checkin', $reservation->id) }}" 
                      method="POST" 
                      class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-medium">
                        Check In
                    </button>
                </form>
                <form action="{{ route('admin.reservation.cancel', $reservation->id) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium">
                        Cancel Booking
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection