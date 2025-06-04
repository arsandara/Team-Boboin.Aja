<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AdminRoomController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

// Home Routes
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/rooms', [UserController::class, 'rooms'])->name('rooms');
Route::get('/facilities', [UserController::class, 'facilities'])->name('facilities');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');

// Reservation/Booking Routes (dengan parameter room_id)
Route::middleware('auth')->group(function () {
    Route::get('/booking/{roomId}', [ReservationController::class, 'create'])->name('booking.create');
    Route::post('/booking', [ReservationController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{id}', [ReservationController::class, 'success'])->name('booking.success');
    Route::get('/booking/success/{id}', [ReservationController::class, 'success'])->name('booking.successfully');

    Route::get('/booking', function(Request $request) {
        if ($request->has('room_id')) {
            return app(ReservationController::class)->create($request->room_id);
        }
        return redirect()->route('rooms');
    })->name('booking.form');
});


    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Reservations
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{id}/edit', [AdminReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{id}', [AdminReservationController::class, 'update'])->name('reservations.update');
        Route::post('/reservations/{id}/checkin', [AdminReservationController::class, 'checkin'])->name('reservations.checkin');
        Route::post('/reservations/{id}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::post('/reservations/{id}/confirm', [AdminReservationController::class, 'markAsConfirmed'])->name('reservations.confirm');
        Route::put('/admin/reservations/{id}', [AdminReservationController::class, 'update'])->name('admin.reservations.update');
        Route::post('/reservations/{id}/paid', [AdminReservationController::class, 'markAsPaid'])->name('reservations.paid');
        Route::delete('/reservations/{id}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');

        // Rooms
        Route::resource('rooms', AdminRoomController::class);
        Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms');
        Route::post('/rooms/{id}/checkout', [AdminRoomController::class, 'checkout'])->name('rooms.checkout');
        Route::get('/admin/rooms', [AdminRoomController::class, 'index'])->name('admin.rooms');
    });


// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});