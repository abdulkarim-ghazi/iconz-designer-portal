<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@iconz.local'],
            ['name' => 'إدارة iConz', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'designer@iconz.local'],
            ['name' => 'مصممة تجريبية', 'password' => Hash::make('password'), 'role' => 'designer']
        );
    }
}
