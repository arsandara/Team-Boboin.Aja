<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Ambil data untuk occupancy per room
        $occupancyData = Room::leftJoin('reservations', function($join) {
            $join->on('rooms.room_name', '=', 'reservations.room_name')
                 ->where('reservations.status', '=', 'Checked In');
        })
        ->select(
            'rooms.room_name',
            'rooms.total_rooms',
            DB::raw('COUNT(reservations.id) as occupied_rooms')
        )
        ->groupBy('rooms.room_name', 'rooms.total_rooms')
        ->get()
        ->map(function($room) {
            return [
                'room_name' => $room->room_name,
                'occupied' => $room->occupied_rooms,
                'available' => $room->total_rooms - $room->occupied_rooms,
                'total' => $room->total_rooms,
                'sold_out' => ($room->total_rooms - $room->occupied_rooms) <= 0
            ];
        });

        // Ambil data untuk today's guest
        $today = Carbon::today()->format('Y-m-d');
        $todayGuest = Reservation::whereDate('check_in', $today)->count();

        // Ambil data untuk new bookings
        $newBooking = Reservation::whereDate('created_at', $today)->count();

        // Ambil data untuk revenue today
        $revenueToday = Reservation::whereDate('check_in', $today)
            ->sum('total_price');

        // Ambil data untuk recent bookings
        $bookings = Reservation::where('status', 'Confirmed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Hitung total rooms dan occupancy percentage
        $totalRooms = Room::sum('total_rooms');
        $totalOccupied = Reservation::where('status', 'Checked In')->count();
        $availabilityPercentage = $totalRooms > 0 
            ? round(($totalRooms - $totalOccupied) / $totalRooms * 100) 
            : 0;

        return view('admin.dashboard', compact(
            'occupancyData',
            'todayGuest',
            'newBooking',
            'revenueToday',
            'bookings',
            'availabilityPercentage'
        ));
    }

    public function rooms(Request $request)
    {
        $search = $request->input('search', '');

        $reservations = Reservation::where('status', 'Checked In')
            ->when($search, function($query) use ($search) {
                return $query->where('guest_name', 'like', "%{$search}%");
            })
            ->orderBy('check_in', 'asc')
            ->get();

        return view('admin.rooms', compact('reservations'));
    }

    public function checkout(Request $request)
    {
        $reservationId = $request->input('reservation_id');

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($reservationId);
            $reservation->status = 'Checked Out';
            $reservation->save();

            // Update room status to available
            $room = $reservation->room;
            $room->status = 'Available';
            $room->save();

            DB::commit();

            return redirect()->route('admin.rooms')->with('success', 'Guest checked out successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
    // GET: /admins
    public function index()
    {
        $admins = Admin::where('deleted', 0)->get();
        return response()->json($admins);
    }

    // POST: /admins
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'deleted'  => 0
        ]);

        return response()->json($admin, 201);
    }

    // GET: /admins/{id}
    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    // PUT: /admins/{id}
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $admin->username = $validated['username'] ?? $admin->username;
        $admin->email    = $validated['email'] ?? $admin->email;

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return response()->json($admin);
    }

    // DELETE: /admins/{id}
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->deleted = 1;
        $admin->save();

        return response()->json(['message' => 'Admin marked as deleted']);
    }
    
}
