<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            AdminUserSeeder::class,
            // PageSectionSeeder::class, // Temporarily disabled due to array conversion error
            MockDataSeeder::class,
        ]);
    }
}
