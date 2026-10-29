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
        Schema::create('clinical_impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('primary_concerns')->nullable(); // ["Anxiety", "Attention Issues", "Mood Changes"]
            $table->string('provisional_diagnosis')->default('To be determined');
            $table->text('differential_diagnosis')->nullable();
            $table->string('icd_dsm_code')->nullable();
            $table->text('case_formulation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_impressions');
    }
};
