<?php

namespace App\Models;

use Database\Factories\CaseHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'patient_id',
    'presenting_complaints',
    'hopi',
    'family_history',
    'personal_history',
    'medical_history',
    'last_updated_date',
])]
class CaseHistory extends Model
{
    /** @use HasFactory<CaseHistoryFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'presenting_complaints' => 'array',
            'family_history' => 'array',
            'personal_history' => 'array',
            'last_updated_date' => 'date',
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
