@extends('layouts.admin_layout')

@section('title', 'Edit Reservation')

@section('content')
<div class="p-6 max-w-3xl mx-auto bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Reservation</h1>

    <form action="{{ route('admin.reservations.update', $reservation->reservation_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block font-medium text-sm mb-1">Guest Name</label>
                <input type="text" value="{{ $reservation->guest_name }}" disabled class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-medium text-sm mb-1">Room Name</label>
                <input type="text" value="{{ $reservation->room_name }}" disabled class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-medium text-sm mb-1">Check-in Date</label>
                <input type="date" name="check_in" value="{{ $reservation->check_in->format('Y-m-d') }}" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-medium text-sm mb-1">Check-out Date</label>
                <input type="date" name="check_out" value="{{ $reservation->check_out->format('Y-m-d') }}" class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="mb-4">
        <label class="block font-medium text-sm mb-2">Additional Requests</label>
        <div class="grid grid-cols-3 gap-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="early_checkin"
                    {{ $reservation->early_checkin ? 'checked disabled' : '' }}
                    class="rounded text-teal-600">
                <span>Early Check-in</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="late_checkout"
                    {{ $reservation->late_checkout ? 'checked disabled' : '' }}
                    class="rounded text-teal-600">
                <span>Late Check-out</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="extra_bed"
                    {{ $reservation->extra_bed ? 'checked disabled' : '' }}
                    class="rounded text-teal-600">
                <span>Extra Bed</span>
            </label>
        </div>
        <p class="text-xs text-gray-500 mt-1">
            Selected requests cannot be unchecked. You can only add more requests.
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 border border-red-400 p-3 rounded mb-4 text-sm">
            <strong>Error:</strong> {{ $errors->first() }}
        </div>
    @endif

    <p class="text-sm text-gray-600 mt-2">
        <strong>Note:</strong> Duration of stay must be the same or longer than the original {{ $reservation->duration }} nights.
    </p>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('admin.reservations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">Update</button>
        </div>
    </form>
</div>
@endsection
