<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminRoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::where('status', 'Checked In');

        if ($request->has('search') && $request->search !== '') {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        $checkedInReservations = $query->orderBy('check_in', 'asc')->get();

        return view('admin.rooms', compact('checkedInReservations'));
    }
    public function checkout($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete(); // atau bisa update status jadi 'Checked Out' kalau ingin disimpan
        return redirect()->route('admin.rooms')->with('success', 'Guest checked out and removed.');
    }
}
