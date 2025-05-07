<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single admin account
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@puskesmas.com',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'phone' => '08123456789',
            'position' => 'Kepala Puskesmas',
            'email_verified_at' => now(),
        ]);

        // Create the admin profile connected to the user
        Admin::create([
            'user_id' => $user->id,
            'department' => 'Management',
            'access_permissions' => 'all',
        ]);
    }
} 