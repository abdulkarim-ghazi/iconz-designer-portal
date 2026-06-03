<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('designers');
    }
};
