<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@yakine-mode.com'],
            [
                'name' => 'Admin Yakine',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Aziz admin user
        User::updateOrCreate(
            ['email' => 'aziz@aziz.com'],
            [
                'name' => 'Aziz Admin',
                'password' => Hash::make('19112002'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Ghaya admin users
        User::updateOrCreate(
            ['email' => 'ghaya@yakinemode.tn'],
            [
                'name' => 'Ghaya Admin 1',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'ghaya@ghaya.com'],
            [
                'name' => 'Ghaya Admin 2',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create worker user
        User::updateOrCreate(
            ['email' => 'worker@yakine-mode.com'],
            [
                'name' => 'Worker Yakine',
                'password' => Hash::make('password'),
                'role' => 'worker',
                'email_verified_at' => now(),
            ]
        );
    }
}