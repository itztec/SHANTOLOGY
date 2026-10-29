<?php

namespace App\Models;

use Database\Factories\ClinicalImpressionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'patient_id',
    'primary_concerns',
    'provisional_diagnosis',
    'differential_diagnosis',
    'icd_dsm_code',
    'case_formulation',
])]
class ClinicalImpression extends Model
{
    /** @use HasFactory<ClinicalImpressionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'primary_concerns' => 'array',
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
