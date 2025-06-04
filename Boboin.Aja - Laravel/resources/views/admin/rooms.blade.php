@extends('layouts.admin_layout')
@section('title', 'Rooms')

@section('content')
<div class="space-y-6">
    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.rooms') }}" class="mb-6">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                placeholder="Search by room number...">
        </div>
    </form>

    @foreach ($checkedInReservations as $room)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-lg font-semibold">{{ $room->room_name }} - {{ $room->room_number }}</h2>
                <p class="text-sm text-gray-500">Guest: <strong>{{ $room->guest_name }}</strong></p>
                <p class="text-sm text-gray-500">Email: {{ $room->guest_email }}</p>
                <p class="text-sm text-gray-500">Check In: {{ \Carbon\Carbon::parse($room->check_in)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-500">Check Out: {{ \Carbon\Carbon::parse($room->check_out)->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600 font-medium">Total: Rp. {{ number_format($room->total_price, 0, ',', '.') }}</p>
                <form action="{{ route('admin.rooms.checkout', $room->reservation_id) }}" method="POST" onsubmit="return confirm('Check out this guest?');">
                    @csrf
                    <button type="submit" class="mt-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Checked Out
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @if ($checkedInReservations->isEmpty())
    <p class="text-center text-gray-600 mt-10">No guests currently checked in.</p>
    @endif
</div>
@endsection
