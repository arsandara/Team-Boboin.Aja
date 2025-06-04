<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::query()->whereIn('status', ['Confirmed', 'Checked In']);

        if ($request->has('search') && $request->search) {
            $query->where('guest_name', 'like', '%' . $request->search . '%');
        }

        $reservations = $query->orderBy('check_in', 'asc')->get();

        // Tambahan statistik
        $today = Carbon::today();

        $todayGuests = Reservation::whereDate('check_in', $today)->count();
        $newBookings = Reservation::whereDate('created_at', $today)->count();
        $revenueToday = Reservation::where('status', 'Confirmed')
            ->whereDate('created_at', $today)
            ->sum('total_price');

        $roomOccupancy = Room::all()->map(function ($room) {
            $occupied = Reservation::where('room_id', $room->room_id)
                ->whereIn('status', ['Checked In'])
                ->count();
            return [
                'room_name' => $room->room_name,
                'occupied' => $occupied,
                'total' => $room->total_rooms,
                'sold_out' => $occupied >= $room->total_rooms,
            ];
        });

        $recentBookings = Reservation::latest()->take(10)->get();

        return view('admin.reservations', compact(
            'reservations',
            'todayGuests',
            'newBookings',
            'revenueToday',
            'roomOccupancy',
            'recentBookings'
        ));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Ambil room terlebih dahulu
        $room = Room::find($reservation->room_id);
        if (!$room) {
            return back()->withErrors(['room' => 'Room data not found for this reservation.'])->withInput();
        }

        // Hitung durasi lama dan baru
        $oldCheckIn = Carbon::parse($reservation->check_in);
        $oldCheckOut = Carbon::parse($reservation->check_out);
        $oldDuration = $oldCheckIn->diffInDays($oldCheckOut);

        $newCheckIn = Carbon::parse($request->check_in);
        $newCheckOut = Carbon::parse($request->check_out);
        $newDuration = $newCheckIn->diffInDays($newCheckOut);

        // Validasi durasi tidak boleh berkurang
        if ($newDuration < $oldDuration) {
            return back()->withErrors([
                'check_out' => "You cannot reduce the number of nights. Original: {$oldDuration} nights, New: {$newDuration} nights."
            ])->withInput();
        }

        // Cek request tambahan
        $early = $request->has('early_checkin') || $reservation->early_checkin;
        $late = $request->has('late_checkout') || $reservation->late_checkout;
        $extra = $request->has('extra_bed') || $reservation->extra_bed;

        // Hitung biaya
        $basePrice = $room->price * $newDuration;
        $addonPrice = 0;
        if ($early) $addonPrice += 50000;
        if ($late) $addonPrice += 50000;
        if ($extra) $addonPrice += 150000;

        $adminFee = 100000;
        $subtotal = $basePrice + $addonPrice + $adminFee;
        $tax = $subtotal * 0.1;
        $newTotal = $subtotal + $tax;
        $oldTotal = $reservation->total_price;
        $selisihBayar = $newTotal - $oldTotal;

        // Backup data lama
        $backupData = [
            'guest_name' => $reservation->guest_name,
            'guest_email' => $reservation->guest_email,
            'guest_phone' => $reservation->guest_phone,
            'guest_dob' => $reservation->guest_dob,
            'room_id' => $reservation->room_id,
            'room_name' => $reservation->room_name ?? $room->room_name,
            'room_number' => $reservation->room_number ?? $room->room_number ?? 'TBD',
            'person' => $reservation->person ?? 1,
        ];

        // Logging untuk debug (boleh dihapus jika tidak perlu)
        \Log::info("Price Calculation:", [
            'room_price' => $room->price,
            'new_duration' => $newDuration,
            'base_price' => $basePrice,
            'addon_price' => $addonPrice,
            'admin_fee' => $adminFee,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'new_total' => $newTotal,
            'old_total' => $oldTotal,
            'selisih' => $selisihBayar
        ]);

        try {
            // Buat data baru
            $newReservation = Reservation::create([
            'guest_name' => $backupData['guest_name'],
            'guest_email' => $backupData['guest_email'],
            'guest_phone' => $backupData['guest_phone'],
            'guest_dob' => $backupData['guest_dob'],
            'room_id' => $backupData['room_id'],
            'room_name' => $backupData['room_name'],
            'room_number' => $backupData['room_number'],
            'person' => $backupData['person'],
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'duration' => $newDuration,
            'early_checkin' => $early,
            'late_checkout' => $late,
            'extra_bed' => $extra,
            'base_price' => $basePrice,
            'request_price' => $addonPrice + $adminFee,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_price' => $newTotal,
            'status' => 'Confirmed',
            'is_paid' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus data lama setelah sukses
        $reservation->delete();

        $message = 'Reservation updated successfully. ';
        if ($selisihBayar > 0) {
        $message .= 'Guest needs to pay additional Rp ' . number_format($selisihBayar, 0, ',', '.');
        } else {
        $message .= 'No additional payment required.';
        }

        return redirect()->route('admin.reservations.index')->with('success', [
            'checkin' => $request->check_in,
            'checkout' => $request->check_out,
            'base_price' => $basePrice,
            'addons' => $addonPrice,
            'admin_fee' => $adminFee,
            'selisih' => $selisihBayar,
        ]);


        } catch (\Exception $e) {
            \Log::error('Failed to update reservation', [
                'error' => $e->getMessage(),
                'reservation_id' => $id
            ]);

            return back()->withErrors(['general' => 'Failed to update reservation: ' . $e->getMessage()])->withInput();
        }
    }

    public function markAsConfirmed($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'Pending') {
            $reservation->status = 'Confirmed';
            $reservation->save();
        }

        return redirect()->back()->with('success', 'Booking Confirmed.');
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);

            // Tambahan validasi (jika mau hanya yang Pending bisa dihapus)
            if ($reservation->status === 'Pending') {
                $reservation->delete();
                return redirect()->back()->with('success', 'Reservation Deleted.');
            }

            return redirect()->back()->with('error', 'Only pending reservations can be deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete reservation.');
        }
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->back()->with('success', 'Reservation Canceled.');
    }

    public function checkin($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'Checked In';
        $reservation->save();

        return redirect()->route('admin.reservations.index')->with('success', 'Guest checked in.');
    }

    public function markAsPaid($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->is_paid = true;
        $reservation->status = 'Confirmed';
        $reservation->save();

        return redirect()->back()->with('success', 'Booking Confirmed.');
    }
}