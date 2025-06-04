<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
{
    $today = \Carbon\Carbon::today();

    // Hitung jumlah tamu hari ini
    $todayGuests = Reservation::whereDate('check_in', $today)->count();

    // Booking baru hari ini
    $newBookings = Reservation::whereDate('created_at', $today)->count();

    // Total revenue hari ini (hanya yg statusnya Confirmed)
    $revenueToday = Reservation::where('status', 'Confirmed')
        ->whereDate('created_at', $today)
        ->sum('total_price');

    // Perhitungan occupancy
    $rooms = Room::all();
    $totalRooms = $rooms->sum('total_rooms');
    $occupiedRooms = Reservation::whereIn('status', ['Checked In'])
        ->whereDate('check_in', $today)
        ->count();

    $availabilityPercentage = $totalRooms > 0
        ? round((($totalRooms - $occupiedRooms) / $totalRooms) * 100)
        : 100;

    // Data untuk tampilan occupancy setiap jenis kamar
    $roomOccupancy = $rooms->map(function ($room) {
        $occupied = Reservation::where('room_id', $room->room_id)
            ->whereIn('status', [ 'Checked In'])
            ->count();

        return [
            'room_name' => $room->room_name,
            'occupied' => $occupied,
            'total' => $room->total_rooms,
            'sold_out' => $occupied >= $room->total_rooms,
        ];
    });

    // Booking terbaru
    $recentBookings = Reservation::whereIn('status', ['Pending', 'Confirmed'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    return view('admin.dashboard', compact(
        'todayGuests',
        'newBookings',
        'availabilityPercentage',
        'revenueToday',
        'roomOccupancy',
        'recentBookings'
    ));
}}