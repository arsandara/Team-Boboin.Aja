@extends('layouts.admin_layout')

@section('title', 'Reservations')
@section('content')
        @if (session('success') && is_array(session('success')))
        @php $s = session('success'); @endphp
        <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded mb-6 shadow">
            <strong class="block text-md font-bold mb-1">Reservation Updated</strong>
            <ul class="text-sm space-y-1">
                <li>Stay: <strong>{{ \Carbon\Carbon::parse($s['checkin'])->format('d M') }} – {{ \Carbon\Carbon::parse($s['checkout'])->format('d M Y') }}</strong> = Rp. {{ number_format($s['base_price'], 0, ',', '.') }}</li>
                <li>Add-ons: Rp. {{ number_format($s['addons'], 0, ',', '.') }}</li>
                <li>Admin Fee: Rp. {{ number_format($s['admin_fee'], 0, ',', '.') }}</li>
                <li><strong>Total Paid Now: Rp. {{ number_format($s['selisih'], 0, ',', '.') }}</strong></li>
            </ul>
        </div>
    @elseif(session('success'))
        <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif
    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.reservations.index') }}" class="mb-6">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                   placeholder="Search guest name...">
        </div>
    </form>

    @foreach ($reservations as $row)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-lg font-semibold">Booking Information</h2>
                <div class="flex items-center mt-1 text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">Booking Confirmed</span>
                </div>
                <p class="text-gray-500 mt-1">{{ $row->created_at->format('F d, Y') }}</p>
            </div>
            <div class="text-right text-gray-600 font-medium">
                Booking ID: MR-{{ strtoupper(substr($row->room_name, 0, 2)) . str_pad($row->reservation_id, 3, '0', STR_PAD_LEFT) }}
            </div>
        </div>
        <div class="mb-4">
            <h3 class="font-medium text-gray-700 mb-2">Guest</h3>
            <p class="font-semibold">{{ $row->guest_name }}</p>
            <p class="text-gray-600">{{ $row->guest_email }}</p>
        </div>

        <table class="w-full text-left border-collapse mb-4">
            <thead>
                <tr class="border-b">
                    <th class="pb-2">Room</th>
                    <th class="pb-2">Room Number</th>
                    <th class="pb-2">Person</th>
                    <th class="pb-2">Check In</th>
                    <th class="pb-2">Check Out</th>
                    <th class="pb-2">Durations</th>
                    <th class="pb-2">Request</th>
                    <th class="pb-2">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-3 font-semibold">{{ $row->room_name }}</td>
                    <td class="py-3">{{ $row->room_number }}</td>
                    <td class="py-3">{{ $row->person }} Adults</td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($row->check_in)->format('M d, Y') }} 14:00 WIB</td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($row->check_out)->format('M d, Y') }} 12:00 WIB</td>
                    <td class="py-3">{{ $row->duration }} Nights</td>
                    <td class="py-3">
                        @php
                            $requests = [];
                            if ($row->early_checkin) $requests[] = 'Early Check In';
                            if ($row->late_checkout) $requests[] = 'Late Check Out';
                            if ($row->extra_bed) $requests[] = 'Extra Bed';
                        @endphp
                        {{ count($requests) ? implode(', ', $requests) : '-' }}
                    </td>
                    <td class="py-3 font-semibold">Rp. {{ number_format($row->base_price, 0, ',', '.') }}/night</td>
                </tr>
            </tbody>
        </table>

        <!-- Price Summary -->
        <div class="mt-1 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3">Price Summary</h3>
            <span class="bg-green-500 text-white px-2 py-1 rounded text-sm font-medium">Paid</span>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Room ({{ $row->duration }} nights):</span>
                    <span class="font-semibold">Rp. {{ number_format($row->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($row->request_price > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Add On:</span>
                    <span class="font-semibold">Rp. {{ number_format($row->request_price, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax 10%:</span>
                    <span class="font-semibold">Rp. {{ number_format($row->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span class="text-gray-700">Total:</span>
                    <span>Rp. {{ number_format($row->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.reservations.edit', $row->reservation_id) }}">
                <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded font-medium transition duration-200">
                    Edit
                </button>
            </a>
            <form action="{{ route('admin.reservations.checkin', $row->reservation_id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-medium">
                    Check In
                </button>
            </form>
            <form action="{{ route('admin.reservations.cancel', $row->reservation_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium">
                    Cancel Booking
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
