<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 3 User Alumni Tiruan untuk Keperluan Simulasi
        /*
        User::updateOrCreate(
            ['email' => 'alumni1@mahasiswa.com'],
            ['name' => 'Budi Setiawan', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'alumni2@mahasiswa.com'],
            ['name' => 'Siti Aminah', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'alumni3@mahasiswa.com'],
            ['name' => 'Rian Hidayat', 'password' => bcrypt('password')]
        );

        // 2. Panggil Seeder Kuesioner Alumni
        $this->call(QuestionnaireSeeder::class);
        */
    }
}