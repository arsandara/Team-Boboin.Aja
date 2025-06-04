<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [
            [
                'room_id' => 1,
                'room_name' => 'Deluxe Cabin',
                'room_number' => '215',
                'room_type' => 'standard',
                'price' => 700000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_1.jpeg.jpeg',
                'image_room' => 'images-booking/room-1.png',
                'total_rooms' => 21
            ],
            [
                'room_id' => 2,
                'room_name' => 'Executive Cabin',
                'room_number' => '225',
                'room_type' => 'standard',
                'price' => 900000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_2.jpeg.png',
                'image_room' => 'images-booking/room-2.png',
                'total_rooms' => 20
            ],
            [
                'room_id' => 3,
                'room_name' => 'Executive Cabin with Jacuzzi',
                'room_number' => '107',
                'room_type' => 'jacuzzi',
                'price' => 1250000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_3.jpeg.png',
                'image_room' => 'images-booking/room-3.png',
                'total_rooms' => 9
            ],
            [
                'room_id' => 4,
                'room_name' => 'Family Cabin',
                'room_number' => '246',
                'room_type' => 'family',
                'price' => 1100000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 4,
                'rating' => 4.9,
                'breakfast_included' => 4,
                'image_booking' => 'images-booking/room_4.jpeg.png',
                'image_room' => 'images-booking/room-4.png',
                'total_rooms' => 15
            ],
            [
                'room_id' => 5,
                'room_name' => 'Family Cabin with Jacuzzi',
                'room_number' => '112',
                'room_type' => 'family',
                'price' => 1500000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 4,
                'rating' => 4.9,
                'breakfast_included' => 4,
                'image_booking' => 'images-booking/room_5.jpeg.png',
                'image_room' => 'images-booking/room-5.png',
                'total_rooms' => 7
            ],
            [
                'room_id' => 6,
                'room_name' => '2 Paws Cabin',
                'room_number' => '255',
                'room_type' => 'pet_friendly',
                'price' => 750000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_6.jpeg.png',
                'image_room' => 'images-booking/room-6.png',
                'total_rooms' => 12
            ],
            [
                'room_id' => 7,
                'room_name' => '4 Paws Cabin',
                'room_number' => '119',
                'room_type' => 'pet_friendly',
                'price' => 1000000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 4,
                'rating' => 4.9,
                'breakfast_included' => 4,
                'image_booking' => 'images-booking/room_7.jpeg.png',
                'image_room' => 'images-booking/room-7.png',
                'total_rooms' => 7
            ],
            [
                'room_id' => 8,
                'room_name' => 'Romantic Cabin',
                'room_number' => '123',
                'room_type' => 'romantic',
                'price' => 1150000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_8.jpeg.png',
                'image_room' => 'images-booking/room-8.png',
                'total_rooms' => 10
            ],
            [
                'room_id' => 9,
                'room_name' => 'Romantic Cabin with Jacuzzi',
                'room_number' => '129',
                'room_type' => 'romantic',
                'price' => 1650000.00,
                'is_available' => 1,
                'created_at' => '2025-03-30 08:00:40',
                'updated_at' => '2025-05-03 02:06:11',
                'capacity' => 2,
                'rating' => 4.9,
                'breakfast_included' => 2,
                'image_booking' => 'images-booking/room_9.jpeg.png',
                'image_room' => 'images-booking/room-9.png',
                'total_rooms' => 7
            ],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->insert($room);
        }
    }
}