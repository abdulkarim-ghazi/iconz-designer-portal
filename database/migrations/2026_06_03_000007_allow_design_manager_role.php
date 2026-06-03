<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'design_manager', 'designer') NOT NULL DEFAULT 'designer'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::table('users')->where('role', 'design_manager')->update(['role' => 'admin']);
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'designer') NOT NULL DEFAULT 'designer'");
        }
    }
};
