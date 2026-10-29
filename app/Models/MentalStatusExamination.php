<?php

namespace App\Models;

use Database\Factories\MentalStatusExaminationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'patient_id',
    'exam_date',
    'general_appearance',
    'psychomotor_behavior',
    'speech',
    'mood_and_affect',
    'thought_process',
    'thought_content',
    'perception',
    'cognitive_functions',
    'insight_and_judgment',
    'clinical_notes',
])]
class MentalStatusExamination extends Model
{
    /** @use HasFactory<MentalStatusExaminationFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
