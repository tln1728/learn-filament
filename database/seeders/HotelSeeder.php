<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'Grand Ocean Hotel',
                'description' => 'A luxurious beachfront hotel with stunning ocean views.',
                'address' => '123 Ocean Drive',
                'city' => 'Miami',
                'country' => 'USA',
                'zip_code' => '33139',
                'rating' => 4.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cityscape Inn',
                'description' => 'Modern hotel in the heart of the city.',
                'address' => '456 Downtown Ave',
                'city' => 'New York',
                'country' => 'USA',
                'zip_code' => '10001',
                'rating' => 4.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('hotels')->insert($hotels);
    }
}
