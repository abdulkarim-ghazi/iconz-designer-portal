<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designer_record_id')->constrained()->cascadeOnDelete();
            $table->string('week_label');
            $table->string('project')->nullable();
            $table->text('positive')->nullable();
            $table->text('negative')->nullable();
            $table->string('flexibility')->nullable();
            $table->boolean('production_error')->default(false);
            $table->text('manager_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_entries');
    }
};
