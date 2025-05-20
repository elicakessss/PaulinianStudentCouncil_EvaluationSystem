<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Administrator::create([
            'id_number' => 'admin123',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'admin@psg.edu.ph',
            'password' => Hash::make('password123'),
            'description' => 'Main system administrator account'
        ]);

        $this->command->info('Admin account created successfully!');
    }
}
