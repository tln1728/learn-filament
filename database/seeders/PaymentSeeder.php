<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            [
                'booking_id' => 1,
                'amount' => 220.00,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 2,
                'amount' => 540.00,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payments')->insert($payments);
    }
}
