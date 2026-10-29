<?php

namespace App\Models;

use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'patient_code',
    'name',
    'age',
    'gender',
    'phone',
    'email',
    'address',
    'guardian_name',
    'guardian_relation',
    'guardian_phone',
    'registration_date',
    'status',
    'avatar_url',
    'notes',
])]
class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
            'age' => 'integer',
        ];
    }

    /**
     * @return HasMany<Appointment, $this>
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->orderBy('scheduled_at', 'desc');
    }

    /**
     * @return HasOne<CaseHistory, $this>
     */
    public function caseHistory(): HasOne
    {
        return $this->hasOne(CaseHistory::class);
    }

    /**
     * @return HasMany<MentalStatusExamination, $this>
     */
    public function mentalStatusExaminations(): HasMany
    {
        return $this->hasMany(MentalStatusExamination::class)->orderBy('exam_date', 'desc');
    }

    /**
     * @return HasMany<PatientAssessment, $this>
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(PatientAssessment::class)->orderBy('assessment_date', 'desc');
    }

    /**
     * @return HasOne<ClinicalImpression, $this>
     */
    public function clinicalImpression(): HasOne
    {
        return $this->hasOne(ClinicalImpression::class);
    }

    /**
     * @return HasMany<GoalPlan, $this>
     */
    public function goalPlans(): HasMany
    {
        return $this->hasMany(GoalPlan::class)->orderBy('created_at', 'asc');
    }

    /**
     * @return HasMany<ProgressReport, $this>
     */
    public function progressReports(): HasMany
    {
        return $this->hasMany(ProgressReport::class)->orderBy('session_number', 'asc');
    }

    /**
     * @param  Builder<Patient>  $query
     * @return Builder<Patient>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('patient_code', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function getNextAppointmentAttribute(): ?Appointment
    {
        return $this->appointments()
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at', 'asc')
            ->first();
    }

    public function getLastVisitAttribute(): ?Appointment
    {
        return $this->appointments()
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'desc')
            ->first();
    }
}
