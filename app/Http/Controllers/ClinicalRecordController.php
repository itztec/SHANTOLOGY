<?php

namespace App\Http\Controllers;

use App\Models\GoalPlan;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClinicalRecordController extends Controller
{
    /**
     * Update Case History (Presenting Complaints, HOPI, Family, Personal history)
     */
    public function updateCaseHistory(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'presenting_complaints' => 'nullable|string',
            'hopi' => 'nullable|string',
            'father_history' => 'nullable|string',
            'mother_history' => 'nullable|string',
            'siblings_history' => 'nullable|string',
            'birth_development' => 'nullable|string',
            'education' => 'nullable|string',
            'social' => 'nullable|string',
            'substance_use' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        // Parse complaints line-by-line into array
        $complaints = [];
        if (! empty($validated['presenting_complaints'])) {
            $complaints = array_values(array_filter(
                array_map('trim', explode("\n", $validated['presenting_complaints']))
            ));
        }

        $familyHistory = [
            'Father' => $validated['father_history'] ?? 'No significant mental illness',
            'Mother' => $validated['mother_history'] ?? 'None',
            'Siblings' => $validated['siblings_history'] ?? 'Healthy',
        ];

        $personalHistory = [
            'Birth & Development' => $validated['birth_development'] ?? 'Normal',
            'Education' => $validated['education'] ?? 'Age appropriate',
            'Social' => $validated['social'] ?? 'Good relationship with peers',
            'Substance Use' => $validated['substance_use'] ?? 'None',
        ];

        $patient->caseHistory()->updateOrCreate(
            ['patient_id' => $patient->id],
            [
                'presenting_complaints' => $complaints,
                'hopi' => $validated['hopi'] ?? null,
                'family_history' => $familyHistory,
                'personal_history' => $personalHistory,
                'medical_history' => $validated['medical_history'] ?? null,
                'last_updated_date' => now(),
            ]
        );

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'case_history'])
            ->with('success', 'Case History successfully updated.');
    }

    /**
     * Save Mental Status Examination (MSE)
     */
    public function storeMse(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'exam_date' => 'required|date',
            'general_appearance' => 'nullable|string',
            'psychomotor_behavior' => 'nullable|string',
            'speech' => 'nullable|string',
            'mood_and_affect' => 'nullable|string',
            'thought_process' => 'nullable|string',
            'thought_content' => 'nullable|string',
            'perception' => 'nullable|string',
            'cognitive_functions' => 'nullable|string',
            'insight_and_judgment' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
        ]);

        $patient->mentalStatusExaminations()->create($validated);

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'mse'])
            ->with('success', 'Mental Status Examination recorded.');
    }

    /**
     * Add Psychometric Assessment
     */
    public function storeAssessment(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'score' => 'nullable|string|max:255',
            'status' => 'required|string|in:assigned,in_progress,completed',
            'assessment_date' => 'required|date',
            'interpretation' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $patient->assessments()->create($validated);

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'assessment'])
            ->with('success', 'Assessment record added.');
    }

    /**
     * Update Clinical Impression & Diagnostic Formulation
     */
    public function updateImpression(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'primary_concerns' => 'nullable|string',
            'provisional_diagnosis' => 'required|string|max:255',
            'differential_diagnosis' => 'nullable|string',
            'icd_dsm_code' => 'nullable|string|max:100',
            'case_formulation' => 'nullable|string',
        ]);

        // Parse comma or newline separated concerns
        $concerns = [];
        if (! empty($validated['primary_concerns'])) {
            $concerns = array_values(array_filter(
                array_map('trim', preg_split('/[,;\n]+/', $validated['primary_concerns']))
            ));
        }

        $patient->clinicalImpression()->updateOrCreate(
            ['patient_id' => $patient->id],
            [
                'primary_concerns' => $concerns,
                'provisional_diagnosis' => $validated['provisional_diagnosis'],
                'differential_diagnosis' => $validated['differential_diagnosis'] ?? null,
                'icd_dsm_code' => $validated['icd_dsm_code'] ?? null,
                'case_formulation' => $validated['case_formulation'] ?? null,
            ]
        );

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'impression'])
            ->with('success', 'Clinical Impression updated.');
    }

    /**
     * Add Goal Plan
     */
    public function storeGoal(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'goal_type' => 'required|string|in:short_term,long_term',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'intervention_modality' => 'nullable|string|max:255',
            'target_date' => 'nullable|date',
            'status' => 'required|string|in:pending,in_progress,achieved',
        ]);

        $patient->goalPlans()->create($validated);

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'goals'])
            ->with('success', 'Therapy goal plan added.');
    }

    /**
     * Update Goal Plan Status
     */
    public function updateGoal(Request $request, Patient $patient, GoalPlan $goal): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,achieved',
        ]);

        $goal->update($validated);

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'goals'])
            ->with('success', 'Goal status updated.');
    }

    /**
     * Save Session Progress Note (SOAP format)
     */
    public function storeProgressReport(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'session_number' => 'required|integer|min:1',
            'session_date' => 'required|date',
            'session_title' => 'nullable|string|max:255',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'homework' => 'nullable|string',
        ]);

        $patient->progressReports()->create($validated);

        return redirect()->route('patients.show', ['patient' => $patient, 'tab' => 'progress'])
            ->with('success', "Session {$validated['session_number']} Progress Note logged.");
    }
}
