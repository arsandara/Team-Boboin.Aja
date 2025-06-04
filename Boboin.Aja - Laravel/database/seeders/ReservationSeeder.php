<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $reservations = [
            [
                'reservation_id' => 12,
                'guest_name' => 'Luki Hermawan',
                'guest_email' => 'luki@email.com',
                'guest_phone' => '08123456712',
                'room_id' => 5,
                'room_name' => 'Family Cabin with Jacuzzi',
                'room_number' => '110',
                'person' => 4,
                'check_in' => '2026-01-25',
                'check_out' => '2026-01-29',
                'duration' => 4,
                'early_checkin' => 0,
                'late_checkout' => 1,
                'extra_bed' => 1,
                'other_request' => null,
                'base_price' => 6000000.00,
                'request_price' => 950000.00,
                'subtotal' => 6950000.00,
                'tax' => 695000.00,
                'total_price' => 7645000.00,
                'status' => 'Confirmed',
                'created_at' => '2025-05-03 02:03:10',
                'updated_at' => '2025-05-03 02:03:49'
            ],
            [
                'reservation_id' => 11,
                'guest_name' => 'Kartika Sari',
                'guest_email' => 'kartika@email.com',
                'guest_phone' => '08123456711',
                'room_id' => 3,
                'room_name' => 'Executive Cabin with Jacuzzi',
                'room_number' => '105',
                'person' => 2,
                'check_in' => '2026-01-20',
                'check_out' => '2026-01-23',
                'duration' => 3,
                'early_checkin' => 1,
                'late_checkout' => 0,
                'extra_bed' => 0,
                'other_request' => null,
                'base_price' => 3750000.00,
                'request_price' => 350000.00,
                'subtotal' => 4100000.00,
                'tax' => 410000.00,
                'total_price' => 4510000.00,
                'status' => 'Confirmed',
                'created_at' => '2025-05-03 02:03:10',
                'updated_at' => '2025-05-03 02:03:49'
            ]
        ];

        // Insert the data
        DB::table('reservations')->insert($reservations);
    }
}