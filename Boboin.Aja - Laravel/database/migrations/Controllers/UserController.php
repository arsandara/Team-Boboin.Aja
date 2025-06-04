<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Room;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Create a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Get a single user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    // Delete user
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted']);
    }

    public function home()
    {
        $rooms = Room::orderBy('room_id', 'asc')->get();
        return view('user.home', compact('rooms'));
    }

    public function rooms(Request $request)
    {
        $filter = $request->query('filter', 'all');
        
        $query = DB::table('rooms')->where('1', '=', '1');
        
        if ($filter !== 'all') {
            if ($filter === 'jacuzzi') {
                $query->where('has_jacuzzi', true);
            } else {
                // For family/romantic, include their jacuzzi versions too
                if ($filter === 'family' || $filter === 'romantic') {
                    $query->where(function($q) use ($filter) {
                        $q->where('room_type', $filter)
                          ->orWhere(function($q) use ($filter) {
                              $q->where('room_name', 'like', '%' . $filter . '%')
                                ->where('room_name', 'like', '%Jacuzzi%');
                          });
                    });
                } else {
                    $query->where('room_type', $filter);
                }
            }
        }
        
        $rooms = $query->orderBy('room_id', 'asc')->get();
        
        return view('room', compact('rooms', 'filter'));
    }
    
    /**
     * Format number to Rupiah currency format
     */
    private function formatRupiah($number)
    {
        return 'Rp. ' . number_format($number, 0, ',', '.');
    }
    
    /**
     * Display the booking page
     */
    public function booking(Request $request)
    {
        $roomId = $request->query('room_id');
        
        $room = DB::table('rooms')->where('room_id', $roomId)->first();
        
        if (!$room) {
            return redirect()->route('rooms')->with('error', 'Room not found');
        }
        
        return view('booking', compact('room'));
    }
    
    /**
     * Display facilities page
     */
    public function facilities()
    {
        return view('facilities');
    }
    
    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }
}

