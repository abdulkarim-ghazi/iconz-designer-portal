<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'designer_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('designer_id')->nullable()->unique()->after('role')->constrained('designers')->nullOnDelete();
            });
        }

        if (Schema::hasTable('users') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'design_manager', 'designer') NOT NULL DEFAULT 'design_manager'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'designer_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('designer_id');
            });
        }

        if (Schema::hasTable('users') && DB::getDriverName() === 'mysql') {
            DB::table('users')->where('role', 'designer')->update(['role' => 'design_manager']);
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'design_manager') NOT NULL DEFAULT 'design_manager'");
        }
    }
};
