<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Uncomment the seeder you want to run:
        
        // For all users (admin, doctor, nurse, staff)
        // $this->call([
        //     UsersTableSeeder::class,
        // ]);
        
        // For only a single admin account
        $this->call([
            AdminSeeder::class,
        ]);
    }
}
