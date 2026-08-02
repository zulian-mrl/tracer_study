<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AkunSuper extends Command
{
    protected $signature = 'akun:super {--email=} {--password=}';

    protected $description = 'Buat/naikkan sebuah akun menjadi Super Admin (jalur pemulihan)';

    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');

        if (!$email || !$password) {
            $this->error('Gunakan: php artisan akun:super --email=... --password=...');
            return self::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Password minimal 8 karakter.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_super' => true,
            ]
        );

        $this->info("Super admin aktif: {$user->email} (ID: {$user->id}).");
        $this->info('Silakan login melalui /login.');

        return self::SUCCESS;
    }
}
