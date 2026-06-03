<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designer_record_id')->constrained()->cascadeOnDelete();
            $table->enum('month_key', ['month1', 'month2', 'month3']);
            $table->unsignedTinyInteger('quality_score')->default(0);
            $table->unsignedTinyInteger('details_score')->default(0);
            $table->unsignedTinyInteger('execution_score')->default(0);
            $table->unsignedTinyInteger('speed_score')->default(0);
            $table->unsignedTinyInteger('brief_score')->default(0);
            $table->unsignedTinyInteger('production_score')->default(0);
            $table->unsignedTinyInteger('followup_score')->default(0);
            $table->unsignedTinyInteger('teamwork_score')->default(0);
            $table->unsignedTinyInteger('flexibility_score')->default(0);
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->json('notes')->nullable();
            $table->json('manager_answers')->nullable();
            $table->timestamps();
            $table->unique(['designer_record_id', 'month_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_evaluations');
    }
};
