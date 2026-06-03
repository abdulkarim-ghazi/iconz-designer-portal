<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('designers')) {
            Schema::create('designers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('job_title')->default('مصمم / مصممة');
                $table->date('start_date')->nullable();
                $table->string('direct_manager')->nullable();
                $table->string('trial_period')->default('3 أشهر');
                $table->decimal('current_salary', 12, 2)->nullable();
                $table->string('proposed_raise')->nullable();
                $table->enum('current_month', ['month1', 'month2', 'month3'])->default('month1');
                $table->enum('status', ['active', 'on_hold', 'completed', 'inactive'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->where('role', 'designer')
                ->orderBy('id')
                ->get()
                ->each(function ($user) {
                    DB::table('designers')->updateOrInsert(
                        ['id' => $user->id],
                        [
                            'name' => $user->name,
                            'email' => $user->email,
                            'job_title' => 'مصمم / مصممة',
                            'trial_period' => '3 أشهر',
                            'current_month' => 'month1',
                            'status' => 'active',
                            'created_at' => $user->created_at ?? now(),
                            'updated_at' => now(),
                        ]
                    );
                });

            if (Schema::hasTable('designer_records')) {
                DB::table('designer_records')
                    ->join('users', 'designer_records.designer_id', '=', 'users.id')
                    ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                    ->distinct()
                    ->orderBy('users.id')
                    ->get()
                    ->each(function ($user) {
                        DB::table('designers')->updateOrInsert(
                            ['id' => $user->id],
                            [
                                'name' => $user->name,
                                'email' => $user->email,
                                'job_title' => 'مصمم / مصممة',
                                'trial_period' => '3 أشهر',
                                'current_month' => 'month1',
                                'status' => 'active',
                                'created_at' => $user->created_at ?? now(),
                                'updated_at' => now(),
                            ]
                        );
                    });
            }
        }

        if (Schema::hasTable('designer_records')) {
            Schema::table('designer_records', function (Blueprint $table) {
                $table->dropForeign(['designer_id']);
            });

            Schema::table('designer_records', function (Blueprint $table) {
                $table->foreign('designer_id')->references('id')->on('designers')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('users')) {
            DB::table('users')->where('role', 'designer')->delete();

            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'design_manager') NOT NULL DEFAULT 'design_manager'");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('designer_records')) {
            Schema::table('designer_records', function (Blueprint $table) {
                $table->dropForeign(['designer_id']);
            });

            Schema::table('designer_records', function (Blueprint $table) {
                $table->foreign('designer_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('users') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'design_manager', 'designer') NOT NULL DEFAULT 'designer'");
        }
    }
};
