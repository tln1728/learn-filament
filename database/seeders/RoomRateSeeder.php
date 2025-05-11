<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomRates = [
            [
                'room_type_id' => 1,
                'date' => Carbon::today(),
                'price' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 2,
                'date' => Carbon::today(),
                'price' => 270.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_type_id' => 3,
                'date' => Carbon::today(),
                'price' => 160.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('room_rates')->insert($roomRates);
    }
}
