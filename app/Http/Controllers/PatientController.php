<?php

namespace App\Http\Controllers;

use App\Models\CaseHistory;
use App\Models\ClinicalImpression;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();
        $query = Patient::with(['appointments' => function ($q) {
            $q->latest('scheduled_at');
        }]);

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $patients = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();

        return view('patients.index', [
            'psychologist' => $psychologist,
            'patients' => $patients,
            'search' => $search,
            'currentStatus' => $status ?? 'all',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();
        // Compute proposed next ID
        $latest = Patient::orderBy('id', 'desc')->first();
        $nextNum = $latest ? ($latest->id + 1) : 1;
        $proposedCode = 'P'.str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);

        return view('patients.create', [
            'psychologist' => $psychologist,
            'proposedCode' => $proposedCode,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|in:M,F,Other',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:30',
            'registration_date' => 'required|date',
            'status' => 'required|string|in:in_progress,completed,follow_up,inactive',
            'notes' => 'nullable|string',
        ]);

        $latest = Patient::orderBy('id', 'desc')->first();
        $nextNum = $latest ? ($latest->id + 1) : 1;
        $validated['patient_code'] = 'P'.str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);

        $patient = Patient::create($validated);

        // Initialize empty Case History & Clinical Impression for clinical documentation
        CaseHistory::create([
            'patient_id' => $patient->id,
            'presenting_complaints' => [],
            'hopi' => null,
            'family_history' => [],
            'personal_history' => [],
            'last_updated_date' => now(),
        ]);

        ClinicalImpression::create([
            'patient_id' => $patient->id,
            'primary_concerns' => [],
            'provisional_diagnosis' => 'To be determined',
        ]);

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->name} ({$patient->patient_code}) successfully created!");
    }

    /**
     * Display the specified resource (Full Clinical EMR Profile).
     */
    public function show(Patient $patient): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        $patient->load([
            'caseHistory',
            'clinicalImpression',
            'mentalStatusExaminations' => function ($q) {
                $q->latest('exam_date');
            },
            'assessments' => function ($q) {
                $q->latest('assessment_date');
            },
            'goalPlans' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
            'progressReports' => function ($q) {
                $q->orderBy('session_number', 'asc');
            },
            'appointments' => function ($q) {
                $q->orderBy('scheduled_at', 'desc');
            },
        ]);

        return view('patients.show', [
            'psychologist' => $psychologist,
            'patient' => $patient,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        return view('patients.edit', [
            'psychologist' => $psychologist,
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|in:M,F,Other',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:30',
            'status' => 'required|string|in:in_progress,completed,follow_up,inactive',
            'notes' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient details successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        $name = $patient->name;
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', "Patient record for {$name} was removed.");
    }
}
