<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call other seeders if they exist
        $this->call([
            AdminSeeder::class,
            // Add other seeders here if needed
        ]);
    }
}
