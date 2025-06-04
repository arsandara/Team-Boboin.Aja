@extends('layouts.layout')

@section('content')
<div class="w-[800px] mx-auto p-4 space-y-6">
    <!-- Header -->
    <div class="text-2xl font-bold text-center mb-6">Review Booking</div>

    <!-- Booking Details -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-green-800 font-semibold">Check In</p>
                <p id="display-checkin" class="font-medium">October 10, 2025 (14.00 WIB)</p>
            </div>
            <div>
                <p class="text-sm text-green-800 font-semibold">Check Out</p>
                <p id="display-checkout" class="font-medium">October 15, 2025 (12.00 WIB)</p>
            </div>
            <div>
                <p class="text-sm text-green-800 font-semibold">Person</p>
                <p id="display-person" class="font-medium">02 Person</p>
            </div>
        </div>
    </div>

    <!-- Room -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex gap-4 p-4">
            <div class="w-1/3">
                <img src="{{ asset('images/' . $room->image_booking) }}" alt="{{ $room->room_name }}" class="w-full rounded-lg">
            </div>
            <div class="w-2/3">
                <h1 class="text-xl font-bold">{{ $room->room_name }}</h1>
                <p class="text-gray-700 mt-2">⭐ {{ $room->rating ?? '0' }}</p>
                <p class="text-gray-700">👥 {{ $room->capacity ?? '0' }} peoples</p>
                @if($room->breakfast_included)
                    <p class="text-gray-700">🍳 Breakfast included</p>
                @endif
                <p class="text-2xl font-bold text-teal-900 mt-2">Rp. {{ number_format($room->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
        <h2 class="text-xl font-bold mb-4">Personal Information</h2>
                @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                <strong>Booking Failed:</strong>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store') }}" id="bookingForm" novalidate>
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">
            <input type="hidden" name="checkin" id="form-checkin">
            <input type="hidden" name="checkout" id="form-checkout">
            <input type="hidden" name="person" id="form-person">

            <!-- First Name -->
            <div>
                <label class="block text-sm font-medium mb-1">First Name<span class="text-red-500">*</span></label>
                <input type="text" name="first_name" class="w-full p-2 border rounded" id="first_name">
                <p class="text-red-500 text-sm hidden" id="first_name_error">Please fill in your first name</p>
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-sm font-medium mb-1">Last Name</label>
                <input type="text" name="last_name" class="w-full p-2 border rounded">
            </div>

            <!-- Date of Birth -->
            <div>
                <label class="block text-sm font-medium mb-1">Date of Birth<span class="text-red-500">*</span></label>
                <div class="flex space-x-2">
                    <input type="text" name="day" class="p-2 border rounded w-16" placeholder="dd" maxlength="2" id="day">
                    <input type="text" name="month" class="p-2 border rounded w-16" placeholder="mm" maxlength="2" id="month">
                    <input type="text" name="year" class="p-2 border rounded w-24" placeholder="yyyy" maxlength="4" id="year">
                </div>
                <p class="text-red-500 text-sm hidden" id="dob_error">Please enter a valid date of birth</p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium mb-1">Email<span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full p-2 border rounded" id="email">
                <p class="text-red-500 text-sm hidden" id="email_error">Please enter a valid email address</p>
            </div>

            <!-- Phone Number -->
            <div>
                <label class="block text-sm font-medium mb-1">Phone Number<span class="text-red-500">*</span></label>
                <input type="tel" name="phone" class="w-full p-2 border rounded" id="phone" placeholder="e.g. 08123456789">
                <p class="text-red-500 text-sm hidden" id="phone_error">Please enter a valid phone number</p>
            </div>

            <!-- Add On Request -->
            <h2 class="text-xl font-bold mt-6 mb-4 border-t border-gray-200 pt-4">Add On Request</h2>
            <div class="space-y-2">
                <label class="flex justify-between items-center">
                    <span><input type="checkbox" name="early_checkin" class="mr-2 addon-checkbox" data-price="350000"> Early Check In (11:00 WIB)</span>
                    <span class="font-medium">Rp. 350.000</span>
                </label>
                <label class="flex justify-between items-center">
                    <span><input type="checkbox" name="late_checkout" class="mr-2 addon-checkbox" data-price="350000"> Late Check Out (15:00 WIB)</span>
                    <span class="font-medium">Rp. 350.000</span>
                </label>
                <label class="flex justify-between items-center">
                    <span><input type="checkbox" name="extra_bed" class="mr-2 addon-checkbox" data-price="150000"> Extra Bed</span>
                    <span class="font-medium">Rp. 150.000</span>
                </label>
            </div>

            <!-- Payment Details -->
            <h2 class="text-xl font-bold mt-6 mb-4 border-t border-gray-200 pt-4">Payment Details</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>{{ $room->room_name }} (<span id="nights-count">1</span> nights)</span>
                    <span id="room-price">Rp. {{ number_format($room->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Request add-on</span>
                    <span id="addons-total">Rp. 0</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (10%)</span>
                    <span id="tax-amount">Rp. {{ number_format($room->price * 0.1, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold border-t pt-2">
                    <span>Total</span>
                    <span id="total-price">Rp. {{ number_format($room->price * 1.1, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="bg-teal-900 text-white px-6 py-3 rounded w-full mt-4 hover:bg-teal-800">
                Booking Room
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookingDetails = {
        checkin: localStorage.getItem('checkinDate') || '{{ date('Y-m-d') }}',
        checkout: localStorage.getItem('checkoutDate') || '{{ date('Y-m-d', strtotime('+1 day')) }}',
        person: localStorage.getItem('personCount') || '02 Person'
    };

    document.getElementById('form-checkin').value = bookingDetails.checkin;
    document.getElementById('form-checkout').value = bookingDetails.checkout;
    document.getElementById('form-person').value = bookingDetails.person;

    document.getElementById('display-checkin').textContent = new Date(bookingDetails.checkin).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ' (14.00 WIB)';
    document.getElementById('display-checkout').textContent = new Date(bookingDetails.checkout).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ' (12.00 WIB)';
    document.getElementById('display-person').textContent = bookingDetails.person;

    const nights = Math.ceil((new Date(bookingDetails.checkout) - new Date(bookingDetails.checkin)) / (1000 * 60 * 60 * 24));
    document.getElementById('nights-count').textContent = nights;
    calculateTotal(nights);

    function calculateTotal(nights) {
        const price = {{ $room->price }};
        let addons = 0;
        document.querySelectorAll('.addon-checkbox:checked').forEach(cb => addons += parseInt(cb.dataset.price));
        const subtotal = price * nights + addons;
        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        document.getElementById('room-price').textContent = 'Rp. ' + (price * nights).toLocaleString('id-ID');
        document.getElementById('addons-total').textContent = 'Rp. ' + addons.toLocaleString('id-ID');
        document.getElementById('tax-amount').textContent = 'Rp. ' + Math.round(tax).toLocaleString('id-ID');
        document.getElementById('total-price').textContent = 'Rp. ' + Math.round(total).toLocaleString('id-ID');
    }

    document.querySelectorAll('.addon-checkbox').forEach(cb => {
        cb.addEventListener('change', () => calculateTotal(nights));
    });
});
</script>
@endsection
