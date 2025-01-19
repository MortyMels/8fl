<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com', // Добавляем email
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
