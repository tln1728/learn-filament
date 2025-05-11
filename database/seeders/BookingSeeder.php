<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'user_id' => 1, // Assumes user with ID 1 exists
                'room_id' => 1,
                'check_in' => Carbon::today(),
                'check_out' => Carbon::today()->addDays(2),
                'total_price' => 220.00,
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'room_id' => 2,
                'check_in' => Carbon::today()->addDays(3),
                'check_out' => Carbon::today()->addDays(5),
                'total_price' => 540.00,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('bookings')->insert($bookings);
    }
}
