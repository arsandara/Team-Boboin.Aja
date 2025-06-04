<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Method untuk halaman Home
    public function home()
    {
        // Ambil semua room dari database
        $rooms = Room::orderBy('room_id', 'asc')->get();
        
        // Kirim data ke view home.blade.php
        return view('user.home', compact('rooms'));
    }

    // Method untuk halaman Rooms
    public function rooms(Request $request)
    {
        // Ambil filter dari URL query string (misal: 'all', 'jacuzzi', 'family', 'romantic')
        $filter = $request->query('filter', 'all');
        
        // Query rooms berdasarkan filter yang dipilih
        $query = Room::query(); // Gunakan Room model untuk mendapatkan data room
        
        if ($filter !== 'all') {
            if ($filter === 'jacuzzi') {
                $query->where('has_jacuzzi', true);
            } else {
                // Untuk family atau romantic, ambil juga versi Jacuzzi-nya
                if ($filter === 'family' || $filter === 'romantic') {
                    $query->where(function($q) use ($filter) {
                        $q->where('room_type', $filter)
                        ->orWhere('room_name', 'like', '%' . $filter . '%')
                        ->where('room_name', 'like', '%Jacuzzi%');
                    });
                } else {
                    $query->where('room_type', $filter);
                }
            }
        }

        // Ambil semua data room berdasarkan filter
        $rooms = $query->orderBy('room_id', 'asc')->get();  // Menggunakan Eloquent

        return view('user.rooms.index', compact('rooms', 'filter'));
    }


    // Method untuk halaman Booking
    public function booking(Request $request)
    {
        // Ambil room_id dari URL query string
        $roomId = $request->query('room_id');
        
        // Cari data room berdasarkan room_id
        $room = DB::table('rooms')->where('room_id', $roomId)->first();
        
        // Jika room tidak ditemukan, redirect ke halaman rooms dengan error
        if (!$room) {
            return redirect()->route('rooms')->with('error', 'Room not found');
        }
        
        // Kirim data room ke view booking.blade.php
        return view('user.booking', compact('room'));
    }

    // Method untuk halaman Facilities
    public function facilities()
    {
        // Kirim ke view facilities.blade.php
        return view('user.facilities');
    }

    // Method untuk halaman Contact
    public function contact()
    {
        // Kirim ke view contact.blade.php
        return view('user.contact');
    }
}
