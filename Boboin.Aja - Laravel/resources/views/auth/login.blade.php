<!-- resources/views/auth/login-popup.blade.php -->
<div id="signin" class="tab-content active">
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="text" required name="email" placeholder="Email" class="w-full p-2 border rounded mb-1">
        @if($errors->has('email'))
            <p class="text-red-500 text-xs mb-2">{{ $errors->first('email') }}</p>
        @endif

        <input type="password" required name="password" placeholder="Password" class="w-full p-2 border rounded mb-1">
        @if($errors->has('password'))
            <p class="text-red-500 text-xs mb-2">{{ $errors->first('password') }}</p>
        @endif
        <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded hover:bg-teal-800">Log In</button>
    </form>
    <p class="text-center mt-4 text-sm">
        Don't have an account? <a href="#" onclick="showTab('signup')" class="text-green-500 hover:underline">Sign Up</a>
    </p>
</div>
