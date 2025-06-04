@extends('layouts.admin_layout')

@section('title', 'Dashboard')
@section('content')
<div class="p-6">
    @if (session('success') && is_array(session('success')))
        @php $s = session('success'); @endphp
    @elseif (session('success'))
        <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif
    <div class="grid grid-cols-4 gap-4">
        <!-- Card Metrics -->
        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <i class="fas fa-user-friends text-2xl text-gray-500"></i>
            <div>
                <p class="text-gray-500">TODAY'S GUEST</p>
                <h2 class="text-2xl font-bold">{{ $todayGuests }}</h2>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <i class="fas fa-calendar-plus text-2xl text-gray-500"></i>
            <div>
                <p class="text-gray-500">NEW BOOKINGS</p>
                <h2 class="text-2xl font-bold">{{ $newBookings }}</h2>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <i class="fas fa-hotel text-2xl text-gray-500"></i>
            <div>
                <p class="text-gray-500">ROOM AVAILABILITY</p>
                <h2 class="text-2xl font-bold">{{ $availabilityPercentage }}%</h2>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <i class="fas fa-dollar-sign text-2xl text-gray-500"></i>
            <div>
                <p class="text-gray-500">REVENUE (TODAY)</p>
                <h2 class="text-2xl font-bold">Rp. {{ number_format($revenueToday, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mt-6">
        <!-- Recent Bookings -->
        <div class="col-span-2 bg-white p-6 rounded shadow">
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
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td class="p-2 border-b">{{ $booking->guest_name }}</td>
                        <td class="p-2 border-b">{{ $booking->room_name }}</td>
                        <td class="p-2 border-b">{{ $booking->check_in->format('M d, Y') }}</td>
                        <td class="p-2 border-b text-yellow-600">{{ $booking->status }}</td>
                        <td class="p-2 border-b">
                            @if ($booking->status === 'Pending')
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.reservations.confirm', $booking->reservation_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 text-sm rounded hover:bg-blue-700">
                                            Mark as Paid
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reservations.destroy', $booking->reservation_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reservation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 text-sm rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @elseif ($booking->status === 'Confirmed')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Confirmed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Room Occupancy -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold mb-4"><i class="fas fa-bed"></i> Room Occupancy</h2>
            <ul>
                @foreach($roomOccupancy as $room)
                <li class="{{ $room['sold_out'] ? 'text-red-600 font-bold' : 'text-green-600' }}">
                    {{ $room['room_name'] }} - {{ $room['occupied'] }}/{{ $room['total'] }} rooms 
                    {{ $room['sold_out'] ? '- SOLD OUT' : '' }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white p-6 rounded w-[400px] space-y-4">
        <h2 class="text-xl font-bold">Edit Reservation</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Previous Check-In</label>
                    <p id="prevCheckIn" class="mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Previous Check-Out</label>
                    <p id="prevCheckOut" class="mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Previous Requests</label>
                    <p id="prevRequest" class="mt-1"></p>
                </div>
                <div>
                    <label for="editCheckIn" class="block text-sm font-medium text-gray-700">New Check-In Date</label>
                    <input type="date" id="editCheckIn" name="check_in" class="mt-1 w-full border rounded p-2" required />
                </div>
                <div>
                    <label for="editCheckOut" class="block text-sm font-medium text-gray-700">New Check-Out Date</label>
                    <input type="date" id="editCheckOut" name="check_out" class="mt-1 w-full border rounded p-2" required />
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded">Save Changes</button>
                    <button type="button" onclick="closeModal()" class="text-red-500">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentReservationId = null;

function openEditModal(id, checkIn, checkOut, request) {
    currentReservationId = id;
    document.getElementById("prevCheckIn").textContent = checkIn;
    document.getElementById("prevCheckOut").textContent = checkOut;
    document.getElementById("prevRequest").textContent = request || 'No special requests';
    document.getElementById("editModal").classList.remove("hidden");
    document.getElementById("editModal").classList.add("flex");

    document.getElementById("editForm").action = `/admin/reservations/${id}`;
}

function closeModal() {
    document.getElementById("editModal").classList.remove("flex");
    document.getElementById("editModal").classList.add("hidden");
}
</script>
@endpush
@endsection
