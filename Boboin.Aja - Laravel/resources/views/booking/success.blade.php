@extends('layouts.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-xl w-full">
        <img src="{{ asset('images/Logogreen.png') }}" alt="Boboin.Aja Logo" class="mx-auto mb-4 w-32">
        <h1 class="text-2xl font-bold text-teal-900 mb-4">Booking Successful!</h1>
        <p class="text-gray-700 mb-6">
            Thank you for booking at <strong>Boboin.Aja</strong>. To confirm your reservation, please proceed with the payment below.
        </p>

        <!-- Payment Details -->
        <div class="bg-gray-100 p-4 rounded-lg mb-6 text-left">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Virtual Account Payment</h2>
            <p class="text-gray-700"><strong>Bank:</strong> {{ $bank_name ?? 'BCA Virtual Account' }}</p>
            <p class="text-gray-700"><strong>Virtual Account Number:</strong> 
                <span class="font-mono tracking-wide">{{ $virtual_account ?? '8860881234567890' }}</span>
            </p>
            <p class="text-gray-700"><strong>Account Holder:</strong> {{ $account_holder ?? 'PT Boboin Global Staycation' }}</p>
            <p class="text-gray-700 mt-4"><strong>Total Amount:</strong> 
                <span class="text-teal-900 font-bold">{{ 'Rp. ' . number_format($total_price ?? 0, 0, ',', '.') }}</span>
            </p>
        </div>

        <!-- Countdown Timer -->
        <p class="text-red-600 font-semibold mb-4">
            Please complete your payment within: <span id="countdown" class="font-mono">15:00</span>
        </p>

        <!-- WhatsApp Instructions -->
        <p class="text-gray-700 mb-4">
            After payment, please send the transfer receipt to our WhatsApp number below:
        </p>

        @php
            $wa = isset($whatsapp) ? ltrim($whatsapp, '+') : '6285175389380';
        @endphp
        <a href="https://wa.me/{{ $wa }}" target="_blank"
           class="inline-block bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition">
            Send via WhatsApp
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let timeLeft = 900; // 15 minutes in seconds

    function updateCountdown() {
        const countdownEl = document.getElementById('countdown');
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.textContent = '00:00';
            alert("Your booking session has expired. Please make a new reservation.");
            window.location.href = "{{ route('rooms') }}";
        }

        timeLeft--;
    }

    const timer = setInterval(updateCountdown, 1000);
    updateCountdown(); // Run immediately on load

    // Function to handle redirection
    const triggerRedirect = () => {
        window.location.href = "{{ route('booking.successfully', ['id' => $reservation->reservation_id ?? 1]) }}";
    };

    // Detect keypress (Enter or Space)
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {  // Only Enter or Space key
            triggerRedirect();
        }
    });

    // Optional: bubble info
    console.log("Waiting for keypress (Enter or Space) to redirect...");
</script>
@endsection
