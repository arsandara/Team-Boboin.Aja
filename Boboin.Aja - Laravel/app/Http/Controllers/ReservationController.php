<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function create($roomId)
    {
        $room = Room::findOrFail($roomId);
        return view('reservations.create', compact('room'));
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $request->merge([
            'early_checkin' => $request->has('early_checkin'),
            'late_checkout' => $request->has('late_checkout'),
            'extra_bed' => $request->has('extra_bed'),
        ]);

        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'day' => 'required|numeric|between:1,31',
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric|between:1900,' . now()->year,
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi tanggal lahir
        if (!checkdate($request->month, $request->day, $request->year)) {
            return redirect()->back()->withErrors(['dob' => 'Tanggal lahir tidak valid'])->withInput();
        }

        $room = Room::findOrFail($request->room_id);
        $roomNumber = $this->generateRoomNumber($room->room_name);

        $checkIn = $request->checkin;
        $checkOut = $request->checkout;
        $duration = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);

        if ($duration < 1) {
            return redirect()->back()->withErrors(['checkin' => 'Durasi menginap minimal 1 malam'])->withInput();
        }

        $basePrice = $room->price * $duration;

        $addonPrice = 0;
        if ($request->early_checkin) $addonPrice += 350000;
        if ($request->late_checkout) $addonPrice += 350000;
        if ($request->extra_bed) $addonPrice += 150000;

        $subtotal = $basePrice + $addonPrice;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        $dob = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request->day, 2, '0', STR_PAD_LEFT);
        $personCount = intval(preg_replace('/[^0-9]/', '', $request->person));

        $reservation = new Reservation();
        $reservation->guest_name = $request->first_name . ' ' . $request->last_name;
        $reservation->guest_email = $request->email;
        $reservation->guest_phone = $request->phone;
        $reservation->guest_dob = $dob;
        $reservation->room_id = $room->room_id;
        $reservation->room_name = $room->room_name;
        $reservation->room_number = $roomNumber;
        $reservation->person = $personCount;
        $reservation->check_in = $checkIn;
        $reservation->check_out = $checkOut;
        $reservation->duration = $duration;
        $reservation->early_checkin = $request->early_checkin;
        $reservation->late_checkout = $request->late_checkout;
        $reservation->extra_bed = $request->extra_bed;
        $reservation->base_price = $basePrice;
        $reservation->request_price = $addonPrice;
        $reservation->subtotal = $subtotal;
        $reservation->tax = $tax;
        $reservation->total_price = $total;
        $reservation->status = 'Pending';
        $reservation->is_paid = false; // Tambahkan kolom ini di database (boolean)
        $reservation->save();

        return view('booking.success', [
            'reservation' => $reservation,
            'bank_name' => 'Mandiri Virtual Account',
            'virtual_account' => '886088' . now()->format('His') . rand(100, 999),
            'account_holder' => 'PT Boboin Global Staycation',
            'total_price' => $total,
            'whatsapp' => '+6285175389380',
            'is_paid' => false,
            'check_in' => $checkIn,
        ]);
    }

    private function generateRoomNumber($roomName)
    {
        $ranges = [
            'Deluxe Cabin' => [200, 220],
            'Executive Cabin' => [221, 240],
            'Family Cabin' => [241, 255],
            '2 Paws Cabin' => [251, 262],
            'Executive Cabin with Jacuzzi' => [100, 108],
            'Family Cabin with Jacuzzi' => [109, 115],
            '4 Paws Cabin' => [116, 122],
            'Romantic Cabin' => [130, 138],
            'Romantic Cabin with Jacuzzi' => [123, 129],
        ];

        if (!isset($ranges[$roomName])) {
            throw new \Exception("Unknown room name: $roomName");
        }

        [$start, $end] = $ranges[$roomName];

        $usedNumbers = Reservation::where('room_name', $roomName)
            ->pluck('room_number')
            ->filter(fn($num) => is_numeric($num))
            ->map(fn($num) => intval($num))
            ->toArray();

        for ($i = $start; $i <= $end; $i++) {
            if (!in_array($i, $usedNumbers)) {
                return strval($i);
            }
        }

        throw new \Exception("No available room number for $roomName");
    }

    public function success($id)
    {
        $reservation = Reservation::findOrFail($id);

        return view('booking.successfully', [
            'total_price' => $reservation->total_price,
            'whatsapp' => '+6285175389380',
            'reservation' => $reservation,
            'is_paid' => $reservation->is_paid,
            'check_in' => $reservation->check_in,
        ]);
    }

    public function index(Request $request)
    {
        $query = Reservation::query()
            ->whereIn('status', ['Pending', 'Confirmed', 'Checked In'])
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
        $reservation->update(['status' => 'Checked In']);

        return redirect()->route('admin.reservation.index')
            ->with('success', 'Guest has been checked in successfully');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
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

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $numberOfDays = $checkOut->diffInDays($checkIn);

        $room = Room::where('room_name', $reservation->room_name)->first();
        $roomPrice = $room->price;

        $total = $roomPrice * $numberOfDays;

        if ($request->has('early_checkin')) $total += 50000;
        if ($request->has('late_checkout')) $total += 50000;
        if ($request->has('extra_bed')) $total += 150000;

        $reservation->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'early_checkin' => $request->has('early_checkin'),
            'late_checkout' => $request->has('late_checkout'),
            'extra_bed' => $request->has('extra_bed'),
            'total_price' => $total
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Reservation updated successfully');
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
