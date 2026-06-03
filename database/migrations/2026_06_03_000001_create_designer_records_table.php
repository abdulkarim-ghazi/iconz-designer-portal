<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designer_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_name');
            $table->string('job_title')->default('مصمم / مصممة');
            $table->date('start_date')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('trial_period')->default('3 أشهر');
            $table->decimal('current_salary', 12, 2)->nullable();
            $table->string('proposed_raise')->nullable();
            $table->enum('current_month', ['month1', 'month2', 'month3'])->default('month1');
            $table->string('customer_name')->nullable();
            $table->string('project_name')->nullable();
            $table->enum('project_status', ['new', 'in_progress', 'waiting_customer', 'sent', 'approved', 'closed'])->default('new');
            $table->text('customer_request')->nullable();
            $table->text('designer_notes')->nullable();
            $table->text('manager_summary')->nullable();
            $table->string('final_decision')->default('لم يتم اتخاذ القرار');
            $table->date('decision_date')->nullable();
            $table->text('decision_reason')->nullable();
            $table->text('next_plan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designer_records');
    }
};
