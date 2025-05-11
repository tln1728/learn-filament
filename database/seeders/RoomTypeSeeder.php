<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'hotel_id' => 1,
                'name' => 'Standard Room',
                'description' => 'Cozy room with essential amenities.',
                'max_occupancy' => 2,
                'base_price' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'name' => 'Deluxe Suite',
                'description' => 'Spacious suite with ocean view.',
                'max_occupancy' => 4,
                'base_price' => 250.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'name' => 'Executive Room',
                'description' => 'Modern room for business travelers.',
                'max_occupancy' => 5,
                'base_price' => 150.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'name' => 'Standard Room',
                'description' => 'Cozy room with essential amenities.',
                'max_occupancy' => 4,
                'base_price' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('room_types')->insert($roomTypes);
    }
}
