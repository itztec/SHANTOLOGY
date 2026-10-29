<?php

namespace Tests\Feature;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MindCareFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboard_page_loads_successfully(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Dhritiman Dey');
        $response->assertSee('Total Patients');
        $response->assertSee('Recent Patients');
        $response->assertSee('Small steps in therapy lead to big changes in life.');
    }

    public function test_patient_directory_lists_patients_and_filters(): void
    {
        $response = $this->get(route('patients.index'));

        $response->assertOk();
        $response->assertSee('Patient Directory');
        $response->assertSee('Riya Sharma');
        $response->assertSee('P001');

        // Test search filter
        $searchResponse = $this->get(route('patients.index', ['search' => 'Riya']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Riya Sharma');
    }

    public function test_patient_clinical_profile_loads_case_history_and_tabs(): void
    {
        $riya = Patient::where('patient_code', 'P001')->firstOrFail();

        $response = $this->get(route('patients.show', $riya));

        $response->assertOk();
        $response->assertSee('Riya Sharma');
        $response->assertSee('P001');
        $response->assertSee('Case History');
        $response->assertSee('Presenting Complaints');
        $response->assertSee('History of Present Illness (HOPI)');
        $response->assertSee('Difficulty in concentrating in school');
        $response->assertSee('Clinical Summary');
    }

    public function test_can_create_new_patient(): void
    {
        $response = $this->post(route('patients.store'), [
            'name' => 'Aditi Sen',
            'age' => 14,
            'gender' => 'F',
            'phone' => '9830012345',
            'email' => 'aditi@example.com',
            'registration_date' => now()->toDateString(),
            'status' => 'in_progress',
            'notes' => 'Social anxiety symptoms.',
        ]);

        $created = Patient::where('name', 'Aditi Sen')->first();
        $this->assertNotNull($created);
        $this->assertStringStartsWith('P', $created->patient_code);
        $response->assertRedirect(route('patients.show', $created));
    }

    public function test_can_update_case_history(): void
    {
        $riya = Patient::where('patient_code', 'P001')->firstOrFail();

        $response = $this->put(route('clinical.case-history.update', $riya), [
            'presenting_complaints' => "Test anxiety\nSleep difficulty",
            'hopi' => 'Updated clinical narrative of symptom development.',
            'father_history' => 'Healthy',
            'mother_history' => 'Anxiety history',
            'education' => '5th Grade Standard',
        ]);

        $response->assertRedirect(route('patients.show', ['patient' => $riya, 'tab' => 'case_history']));

        $riya->refresh();
        $this->assertEquals('Updated clinical narrative of symptom development.', $riya->caseHistory->hopi);
        $this->assertContains('Test anxiety', $riya->caseHistory->presenting_complaints);
    }

    public function test_calendar_and_appointment_booking(): void
    {
        $riya = Patient::where('patient_code', 'P001')->firstOrFail();

        $response = $this->get(route('calendar.index'));
        $response->assertOk();
        $response->assertSee('Clinical Calendar');

        $booking = $this->post(route('appointments.store'), [
            'patient_id' => $riya->id,
            'scheduled_date' => now()->addDays(2)->toDateString(),
            'scheduled_time' => '14:00',
            'duration_minutes' => 45,
            'session_type' => 'Review & Maintenance Session',
            'notes' => 'Follow up on grounding homework.',
        ]);

        $booking->assertRedirect();
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $riya->id,
            'session_type' => 'Review & Maintenance Session',
        ]);
    }

    public function test_reports_and_printable_summary(): void
    {
        $riya = Patient::where('patient_code', 'P001')->firstOrFail();

        $indexResponse = $this->get(route('reports.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Clinical Reports');

        $summaryResponse = $this->get(route('reports.patient-summary', $riya));
        $summaryResponse->assertOk();
        $summaryResponse->assertSee('Confidential Psychological Evaluation');
        $summaryResponse->assertSee('Riya Sharma');
    }

    public function test_backup_download(): void
    {
        $response = $this->get(route('backup.download'));

        $response->assertOk();
        $this->assertStringContainsString('application/json', $response->headers->get('content-type'));
    }
}
