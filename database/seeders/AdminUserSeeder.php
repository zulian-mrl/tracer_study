<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@umm.ac.id'],
            [
                'name' => 'Admin Tracer Study',
                'password' => env('ADMIN_PASSWORD'),
            ]
        );
    }
}
