<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mental_status_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('exam_date');
            $table->text('general_appearance')->nullable();
            $table->text('psychomotor_behavior')->nullable();
            $table->text('speech')->nullable();
            $table->text('mood_and_affect')->nullable();
            $table->text('thought_process')->nullable();
            $table->text('thought_content')->nullable();
            $table->text('perception')->nullable();
            $table->text('cognitive_functions')->nullable();
            $table->text('insight_and_judgment')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mental_status_examinations');
    }
};
