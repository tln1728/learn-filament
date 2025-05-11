<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'user_id' => 1,
                'hotel_id' => 1,
                'booking_id' => 1,
                'rating' => 5,
                'comment' => 'Amazing stay, great service!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'hotel_id' => 2,
                'booking_id' => 2,
                'rating' => 4,
                'comment' => 'Very comfortable, but room service was slow.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reviews')->insert($reviews);
    }
}
