<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(Room::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name'          => 'required|string|max:255',
            'room_number'        => 'required|string',
            'room_type'          => 'required|string',
            'price'              => 'required|numeric',
            'capacity'           => 'required|integer',
            'rating'             => 'nullable|numeric',
            'image_booking'      => 'nullable|string',
            'image_room'         => 'nullable|string',
            'breakfast_included' => 'nullable|boolean',
            'total_rooms'        => 'nullable|integer',
        ]);

        $room = Room::create($validated);
        return response()->json($room, 201);
    }

    public function show($id)
    {
        return response()->json(Room::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'room_name'          => 'sometimes|string|max:255',
            'room_number'        => 'sometimes|string',
            'room_type'          => 'sometimes|string',
            'price'              => 'sometimes|numeric',
            'capacity'           => 'sometimes|integer',
            'rating'             => 'nullable|numeric',
            'image_booking'      => 'nullable|string',
            'image_room'         => 'nullable|string',
            'breakfast_included' => 'nullable|boolean',
            'total_rooms'        => 'nullable|integer',
        ]);

        $room->update($validated);
        return response()->json($room);
    }

    public function destroy($id)
    {
        Room::destroy($id);
        return response()->json(['message' => 'Room deleted']);
    }
}
