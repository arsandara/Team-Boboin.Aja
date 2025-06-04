<!-- resources/views/auth/register.blade.php -->
<div id="signup" class="tab-content hidden">
    <form action="{{ route('register') }}" method="POST">
        @csrf
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <input type="text" 
                   name="name" 
                   value="{{ old('name') }}"
                   required 
                   placeholder="Full Name" 
                   class="w-full p-2 border rounded mb-1 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <input type="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   required 
                   placeholder="Email" 
                   class="w-full p-2 border rounded mb-1 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <input type="password" 
                   name="password" 
                   required 
                   placeholder="Password" 
                   class="w-full p-2 border rounded mb-1 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <input type="password" 
                   name="password_confirmation" 
                   required 
                   placeholder="Confirm Password" 
                   class="w-full p-2 border rounded mb-1">
        </div>
        
        <button type="submit" class="w-full bg-teal-900 text-white py-2 rounded hover:bg-teal-800">
            Sign Up
        </button>
    </form>
    <p class="text-center mt-4 text-sm">
        Already have an account? <a href="#" onclick="showTab('signin')" class="text-green-500 hover:underline">Log In</a>
    </p>
</div>
