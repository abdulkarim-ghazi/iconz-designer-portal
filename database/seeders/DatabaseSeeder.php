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
            ['name' => 'المدير العام', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'design-manager@iconz.local'],
            ['name' => 'مدير التصميم', 'password' => Hash::make('password'), 'role' => 'design_manager']
        );

        User::firstOrCreate(
            ['email' => 'designer@iconz.local'],
            ['name' => 'مصممة تجريبية', 'password' => Hash::make('password'), 'role' => 'designer', 'is_active' => false]
        );
    }
}
