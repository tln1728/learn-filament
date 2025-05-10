<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'admin',
            'email' => 'admin@example.com',
            'password' => '123',
        ])->assignRole('admin');

        User::factory()->create([
            'name'  => 'lam',
            'email' => 'tunglamnoibai@gmail.com',
            'password' => '123',
        ])->assignRole('staff');

        User::factory(10)->create();
    }
}
