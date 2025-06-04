<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display the reservation form for the specified room.
     *
     * @param  int  $roomId
     * @return \Illuminate\View\View
     */
    public function create($roomId)
    {
        $room = Room::findOrFail($roomId);
        return view('reservations.create', compact('room'));
    }

    /**
     * Store a new reservation in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'day' => 'required|numeric|between:01,31',
            'month' => 'required|numeric|between:01,12',
            'year' => 'required|numeric|between:1900,2023',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:15',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'person' => 'required|string',
            'early_checkin' => 'sometimes|boolean',
            'late_checkout' => 'sometimes|boolean',
            'extra_bed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the room details
        $room = Room::findOrFail($request->room_id);
        
        // Calculate the duration and prices
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;
        $duration = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $basePrice = $room->price * $duration;
        
        $addonPrice = 0;
        if ($request->early_checkin) $addonPrice += 350000;
        if ($request->late_checkout) $addonPrice += 350000;
        if ($request->extra_bed) $addonPrice += 150000;
        
        $subtotal = $basePrice + $addonPrice;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;
        
        // Format DOB
        $dob = $request->year . '-' . $request->month . '-' . $request->day;
        
        // Extract the person count as numeric
        $personCount = intval(preg_replace('/[^0-9]/', '', $request->person));
        
        // Create the reservation
        $reservation = new Reservation();
        $reservation->guest_name = $request->first_name . ' ' . $request->last_name;
        $reservation->guest_email = $request->email;
        $reservation->guest_phone = $request->phone;
        $reservation->guest_dob = $dob;
        $reservation->room_id = $room->room_id;
        $reservation->room_name = $room->room_name;
        $reservation->room_number = $room->room_number;
        $reservation->person = $personCount;
        $reservation->check_in = $checkIn;
        $reservation->check_out = $checkOut;
        $reservation->duration = $duration;
        $reservation->early_checkin = $request->early_checkin ? 1 : 0;
        $reservation->late_checkout = $request->late_checkout ? 1 : 0;
        $reservation->extra_bed = $request->extra_bed ? 1 : 0;
        $reservation->base_price = $basePrice;
        $reservation->request_price = $addonPrice;
        $reservation->subtotal = $subtotal;
        $reservation->tax = $tax;
        $reservation->total_price = $total;
        $reservation->status = 'Confirmed';
        $reservation->save();
        
        // Redirect with success message
        return redirect()->route('home')
            ->with('success', 'Booking room was successful. Thank you for booking at Boboin.Aja!');
    }
    public function index(Request $request)
    {
        $query = Reservation::query()
            ->whereIn('status', ['Confirmed', 'Checked In'])
            ->orderBy('check_in', 'asc');

        if ($request->has('search')) {
            $query->where('guest_name', 'like', '%' . $request->search . '%');
        }

        $reservations = $query->get();

        return view('admin.reservation.index', compact('reservations'));
    }

    public function checkin($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Update status to Checked In
        $reservation->update([
            'status' => 'Checked In'
        ]);

        // Bisa tambahkan logic lain seperti:
        // - Update room status
        // - Create check-in record
        // - dll

        return redirect()->route('admin.reservation.index')
            ->with('success', 'Guest has been checked in successfully');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Soft delete atau hard delete
        $reservation->delete();

        return redirect()->route('admin.reservation.index')
            ->with('success', 'Reservation has been cancelled');
    }
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservation.edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $reservation = Reservation::findOrFail($id);
        $oldTotal = $reservation->total_price;
        
        // Hitung jumlah hari
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $numberOfDays = $checkOut->diffInDays($checkIn);

        // Ambil harga kamar
        $room = Room::where('room_name', $reservation->room_name)->first();
        $roomPrice = $room->price;

        // Hitung total harga baru
        $total = $roomPrice * $numberOfDays;
        
        // Tambah biaya tambahan
        if ($request->has('early_checkin')) $total += 50000;
        if ($request->has('late_checkout')) $total += 50000;
        if ($request->has('extra_bed')) $total += 150000;

        // Update reservation
        $reservation->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'early_checkin' => $request->has('early_checkin'),
            'late_checkout' => $request->has('late_checkout'),
            'extra_bed' => $request->has('extra_bed'),
            'total_price' => $total
        ]);

        // Update revenue
        $revenue = Revenue::where('date', Carbon::today()->format('Y-m-d'))->first();
        if ($revenue) {
            $revenue->total_amount = $revenue->total_amount - $oldTotal + $total;
            $revenue->save();
        } else {
            Revenue::create([
                'date' => Carbon::today()->format('Y-m-d'),
                'total_amount' => $total
            ]);
        }

        return redirect()->route('dashboard')
                        ->with('success', 'Reservation updated successfully');
    }
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Jika perlu update revenue, lakukan di sini
            $revenue = Revenue::where('date', Carbon::today()->format('Y-m-d'))->first();
            if ($revenue) {
                $revenue->total_amount -= $reservation->total_price;
                $revenue->save();
            }
            
            $reservation->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}