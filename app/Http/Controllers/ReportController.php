<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientAssessment;
use App\Models\User;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    /**
     * Display clinic analytics and report generation portal.
     */
    public function index(): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        $totalPatients = Patient::count();
        $completedCases = Patient::where('status', 'completed')->count();
        $inProgressCases = Patient::where('status', 'in_progress')->count();
        $followUpCases = Patient::where('status', 'follow_up')->count();

        $totalAssessments = PatientAssessment::count();
        $completedAssessments = PatientAssessment::where('status', 'completed')->count();

        $patients = Patient::with(['clinicalImpression', 'appointments'])->orderBy('name', 'asc')->get();

        return view('reports.index', [
            'psychologist' => $psychologist,
            'totalPatients' => $totalPatients,
            'completedCases' => $completedCases,
            'inProgressCases' => $inProgressCases,
            'followUpCases' => $followUpCases,
            'totalAssessments' => $totalAssessments,
            'completedAssessments' => $completedAssessments,
            'patients' => $patients,
        ]);
    }

    /**
     * Display printable psychological case report for a patient.
     */
    public function patientSummary(Patient $patient): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        $patient->load([
            'caseHistory',
            'clinicalImpression',
            'mentalStatusExaminations' => fn ($q) => $q->latest('exam_date'),
            'assessments' => fn ($q) => $q->latest('assessment_date'),
            'goalPlans' => fn ($q) => $q->orderBy('created_at', 'asc'),
            'progressReports' => fn ($q) => $q->orderBy('session_number', 'asc'),
        ]);

        return view('reports.patient-summary', [
            'psychologist' => $psychologist,
            'patient' => $patient,
        ]);
    }
}
