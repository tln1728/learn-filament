<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'hotel_id' => 1,
                'room_type_id' => 1,
                'room_number' => '101',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'room_type_id' => 1,
                'room_number' => '102',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'room_type_id' => 1,
                'room_number' => '103',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'room_type_id' => 2,
                'room_number' => '201',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'room_type_id' => 2,
                'room_number' => '202',
                'is_available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'room_type_id' => 4,
                'room_number' => '101',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'room_type_id' => 4,
                'room_number' => '102',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'room_type_id' => 3,
                'room_number' => '201',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('rooms')->insert($rooms);
    }
}
