<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a calendar and agenda listing.
     */
    public function index(Request $request): View
    {
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $appointments = Appointment::with('patient')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $patients = Patient::orderBy('name', 'asc')->get(['id', 'patient_code', 'name']);

        return view('appointments.index', [
            'psychologist' => $psychologist,
            'appointments' => $appointments,
            'selectedDate' => $selectedDate,
            'patients' => $patients,
        ]);
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|string',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'session_type' => 'required|string|max:100',
            'session_number' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $scheduledAt = Carbon::parse($validated['scheduled_date'].' '.$validated['scheduled_time']);
        $psychologist = User::where('role', 'psychologist')->first() ?? User::first();

        // Calculate session number if not provided
        $sessionNumber = $validated['session_number'] ?? (
            Appointment::where('patient_id', $validated['patient_id'])->count() + 1
        );

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'user_id' => $psychologist?->id,
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => $validated['duration_minutes'],
            'session_number' => $sessionNumber,
            'session_type' => $validated['session_type'],
            'status' => 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Appointment successfully scheduled.');
    }

    /**
     * Update the specified appointment status.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:scheduled,completed,cancelled,no_show',
        ]);

        $appointment->update($validated);

        return redirect()->back()->with('success', 'Appointment updated.');
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->back()->with('success', 'Appointment deleted.');
    }
}
