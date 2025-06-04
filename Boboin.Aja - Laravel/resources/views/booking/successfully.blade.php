@extends('layouts.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-100 via-white to-teal-50">
    <div class="bg-white p-10 rounded-xl shadow-xl text-center max-w-xl w-full">
        <img src="{{ asset('images/Logogreen.png') }}" alt="Boboin.Aja Logo" class="mx-auto mb-6 w-24">

        <h1 class="text-3xl font-bold text-teal-800 mb-2">Booking Confirmed!</h1>
        <p class="text-gray-700 text-lg mb-6">Thank you for your payment. Your stay with <strong>Boboin.Aja</strong> is now confirmed.</p>

        <div class="text-left bg-teal-50 border-l-4 border-teal-500 p-4 rounded mb-6">
            <h2 class="font-semibold text-teal-700 mb-1">Booking Details</h2>
            <p><strong>Guest Name:</strong> {{ $reservation->guest_name }}</p>
            <p><strong>Room:</strong> {{ $reservation->room_name }} (No. {{ $reservation->room_number }})</p>
            <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}</p>
            <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</p>
            <p><strong>Total Paid:</strong> Rp. {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
        </div>

        <a href="{{ route('home') }}"
           class="inline-block mt-4 bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700 transition">
            Back to Home
        </a>

        <p class="mt-6 text-gray-500 text-sm">
            If you have any questions, please contact our support via WhatsApp: <br>
            <strong><a href="https://wa.me/{{ ltrim($whatsapp ?? '6285175389380', '+') }}" class="text-teal-700 hover:underline">
                {{ $whatsapp ?? '+62 851-7538-9380' }}
            </a></strong>
        </p>
    </div>
</div>
@endsection
