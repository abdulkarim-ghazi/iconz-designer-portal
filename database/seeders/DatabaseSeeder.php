<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Designer;
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

        Designer::firstOrCreate(
            ['name' => 'مصممة تجريبية'],
            ['job_title' => 'مصمم / مصممة', 'trial_period' => '3 أشهر', 'current_month' => 'month1', 'status' => 'active']
        );
    }
}
