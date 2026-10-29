<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\CaseHistory;
use App\Models\ClinicalImpression;
use App\Models\GoalPlan;
use App\Models\MentalStatusExamination;
use App\Models\Patient;
use App\Models\PatientAssessment;
use App\Models\ProgressReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clinician / Admin Profile (Matches user credentials: id 'admin', pass '1234')
        $doctor = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Dhritiman Dey',
                'email' => 'admin@mindcare.com',
                'title' => 'Psychologist',
                'role' => 'psychologist',
                'phone' => '+91 98765 00000',
                'password' => Hash::make('1234'),
            ]
        );

        $today = Carbon::today();

        // 2. Patient 1: Riya Sharma (P001) - Detailed clinical case from screenshot 2
        $riya = Patient::updateOrCreate(
            ['patient_code' => 'P001'],
            [
                'name' => 'Riya Sharma',
                'age' => 10,
                'gender' => 'F',
                'phone' => '9876543210',
                'email' => 'parents.riya@example.com',
                'address' => 'Salt Lake Sector 1, Kolkata',
                'guardian_name' => 'Alok Sharma',
                'guardian_relation' => 'Father',
                'guardian_phone' => '9876543210',
                'registration_date' => Carbon::create(2025, 10, 1),
                'status' => 'in_progress',
                'notes' => 'Child referred by school counselor for acute school-related anxiety and somatic complaints.',
            ]
        );

        CaseHistory::updateOrCreate(
            ['patient_id' => $riya->id],
            [
                'presenting_complaints' => [
                    'Difficulty in concentrating in school',
                    'Often feels anxious and worries about small things',
                    'Frequent mood changes and irritability',
                ],
                'hopi' => 'Symptoms started around 6 months back, initially noticed by parents as increased anxiety and reduced interest in studies. The child also reports difficulty in sleep and occasional headache. No history of self-harm or suicidal ideation.',
                'family_history' => [
                    'Father' => 'No significant mental illness',
                    'Mother' => 'History of anxiety (on treatment)',
                    'Siblings' => 'Younger brother (healthy)',
                ],
                'personal_history' => [
                    'Birth & Development' => 'Normal',
                    'Education' => '5th Grade',
                    'Social' => 'Good relationship with friends',
                    'Substance Use' => 'None',
                ],
                'medical_history' => 'Occasional tension-type headaches, pediatric checkup cleared organic causes.',
                'last_updated_date' => Carbon::create(2025, 10, 1),
            ]
        );

        ClinicalImpression::updateOrCreate(
            ['patient_id' => $riya->id],
            [
                'primary_concerns' => ['Anxiety', 'Attention Issues', 'Mood Changes'],
                'provisional_diagnosis' => 'To be determined',
                'differential_diagnosis' => 'Generalized Anxiety Disorder vs Adjustment Disorder with Anxious Mood',
                'icd_dsm_code' => 'ICD-11: 6B00 / DSM-5: 300.02',
                'case_formulation' => 'Predisposing maternal anxiety history with precipitating academic pressure transition into 5th grade.',
            ]
        );

        MentalStatusExamination::updateOrCreate(
            ['patient_id' => $riya->id, 'exam_date' => Carbon::create(2025, 10, 1)],
            [
                'general_appearance' => 'Well-groomed 10-year-old girl in age-appropriate attire, cooperative, good eye contact.',
                'psychomotor_behavior' => 'Mild motor agitation noted in fidgeting fingers during discussions of exams.',
                'speech' => 'Spontaneous, coherent, clear articulation with normal rate and volume.',
                'mood_and_affect' => 'Mood described as "worried". Affect congruent, reactive, and anxious.',
                'thought_process' => 'Linear and logical, goal-oriented.',
                'thought_content' => 'Preoccupied with academic performance and fear of negative evaluation. No suicidal ideation.',
                'perception' => 'No auditory or visual hallucinations.',
                'cognitive_functions' => 'Alert, fully oriented x3. Attention sustained with mild distraction.',
                'insight_and_judgment' => 'Fair insight into emotional state, good social judgment.',
                'clinical_notes' => 'MSE consistent with significant anxiety and somatic tension.',
            ]
        );

        PatientAssessment::updateOrCreate(
            ['patient_id' => $riya->id, 'assessment_name' => 'GAD-7 (Generalized Anxiety Scale)'],
            [
                'category' => 'Anxiety Screen',
                'score' => '13/21 - Moderate Anxiety',
                'status' => 'completed',
                'assessment_date' => Carbon::create(2025, 10, 1),
                'interpretation' => 'Indicates moderate clinically significant generalized anxiety symptoms.',
                'recommendations' => 'Cognitive behavioral psychoeducation and somatic relaxation training.',
            ]
        );

        PatientAssessment::updateOrCreate(
            ['patient_id' => $riya->id, 'assessment_name' => 'Vanderbilt ADHD Parent Rating Scale'],
            [
                'category' => 'Behavioral Rating',
                'score' => 'Elevated inattentive scores secondary to anxiety',
                'status' => 'completed',
                'assessment_date' => Carbon::create(2025, 10, 2),
                'interpretation' => 'Attention deficits appear secondary to internal anxious preoccupation rather than primary ADHD.',
            ]
        );

        PatientAssessment::updateOrCreate(
            ['patient_id' => $riya->id, 'assessment_name' => 'Child Depression Inventory (CDI-2)'],
            [
                'category' => 'Mood Screen',
                'score' => 'Pending review',
                'status' => 'in_progress',
                'assessment_date' => Carbon::create(2025, 10, 5),
                'interpretation' => 'Evaluation of low mood secondary to anxiety burden.',
            ]
        );

        GoalPlan::updateOrCreate(
            ['patient_id' => $riya->id, 'title' => 'Anxiety Psychoeducation & Normalization'],
            [
                'goal_type' => 'short_term',
                'description' => 'Help child and parents identify anxiety triggers and understand somatic manifestations.',
                'intervention_modality' => 'Cognitive Behavioral Therapy (CBT)',
                'target_date' => Carbon::create(2025, 10, 20),
                'status' => 'in_progress',
            ]
        );

        GoalPlan::updateOrCreate(
            ['patient_id' => $riya->id, 'title' => 'Grounding & Diaphragmatic Breathing Mastery'],
            [
                'goal_type' => 'short_term',
                'description' => 'Practice and establish 5-4-3-2-1 sensory grounding and square breathing during distress.',
                'intervention_modality' => 'Mindfulness & Somatic Regulation',
                'target_date' => Carbon::create(2025, 10, 25),
                'status' => 'in_progress',
            ]
        );

        GoalPlan::updateOrCreate(
            ['patient_id' => $riya->id, 'title' => 'School Test Exposure & Cognitive Restructuring'],
            [
                'goal_type' => 'long_term',
                'description' => 'Graded hierarchy exposure to exam situations with positive self-talk statements.',
                'intervention_modality' => 'Exposure Therapy & CBT',
                'target_date' => Carbon::create(2025, 11, 30),
                'status' => 'pending',
            ]
        );

        $appt1 = Appointment::updateOrCreate(
            ['patient_id' => $riya->id, 'session_number' => 1],
            [
                'user_id' => $doctor->id,
                'scheduled_at' => $today->copy()->setTime(10, 0),
                'duration_minutes' => 50,
                'session_type' => 'Intake & Clinical Assessment',
                'status' => 'scheduled',
                'notes' => 'Session 1: Detailed intake with parents and child.',
            ]
        );

        // Future appointment
        Appointment::updateOrCreate(
            ['patient_id' => $riya->id, 'session_number' => 2],
            [
                'user_id' => $doctor->id,
                'scheduled_at' => Carbon::create(2025, 11, 15, 10, 0),
                'duration_minutes' => 50,
                'session_type' => 'CBT Grounding & Psychoeducation',
                'status' => 'scheduled',
            ]
        );

        ProgressReport::updateOrCreate(
            ['patient_id' => $riya->id, 'session_number' => 1],
            [
                'appointment_id' => $appt1->id,
                'session_date' => Carbon::create(2025, 10, 1),
                'session_title' => 'Session 1: Clinical Intake & Rapport Building',
                'subjective' => 'Patient expressed reluctance initially but warmed up. Reported fear of failing tests and making mother upset.',
                'objective' => 'Mild motor fidgeting. Spoke politely with soft voice. Completed GAD-7 screener with guidance.',
                'assessment' => 'Clinical picture strongly suggests generalized anxiety with school performance triggers.',
                'plan' => 'Begin psychoeducation on "the worry brain" and introduce belly breathing.',
                'homework' => 'Draw a picture of what worry feels like in the body.',
            ]
        );

        // 3. Patient 2: Arjun Das (P002) - 16 / M
        $arjun = Patient::updateOrCreate(
            ['patient_code' => 'P002'],
            [
                'name' => 'Arjun Das',
                'age' => 16,
                'gender' => 'M',
                'phone' => '9807654321',
                'email' => 'arjun.das@example.com',
                'registration_date' => Carbon::create(2025, 9, 10),
                'status' => 'completed',
                'notes' => 'Adolescent exam stress and sleep disruption protocol completed successfully.',
            ]
        );

        CaseHistory::updateOrCreate(
            ['patient_id' => $arjun->id],
            [
                'presenting_complaints' => ['Severe insomnia before weekly exams', 'Physical restlessness', 'Self-criticism'],
                'hopi' => '11th grade student struggling with competitive entrance exam preparation pressure.',
                'family_history' => ['Father' => 'Engineer', 'Mother' => 'Teacher', 'Siblings' => 'Elder sister (in college)'],
                'personal_history' => ['Education' => '11th Grade Science', 'Social' => 'Active in football', 'Substance Use' => 'None'],
                'last_updated_date' => Carbon::create(2025, 9, 30),
            ]
        );

        ClinicalImpression::updateOrCreate(
            ['patient_id' => $arjun->id],
            [
                'primary_concerns' => ['Exam Stress', 'Sleep Disruption', 'Perfectionism'],
                'provisional_diagnosis' => 'Adjustment Disorder with Anxious Mood',
            ]
        );

        Appointment::updateOrCreate(
            ['patient_id' => $arjun->id, 'session_number' => 2],
            [
                'user_id' => $doctor->id,
                'scheduled_at' => $today->copy()->setTime(11, 30),
                'duration_minutes' => 45,
                'session_type' => 'Review & Maintenance Session',
                'status' => 'scheduled',
            ]
        );

        // 4. Patient 3: Sneha Roy (P003) - 28 / F
        $sneha = Patient::updateOrCreate(
            ['patient_code' => 'P003'],
            [
                'name' => 'Sneha Roy',
                'age' => 28,
                'gender' => 'F',
                'phone' => '9876543212',
                'email' => 'sneha.roy@example.com',
                'registration_date' => Carbon::create(2025, 9, 15),
                'status' => 'in_progress',
                'notes' => 'Corporate burnout and chronic workplace stress.',
            ]
        );

        CaseHistory::updateOrCreate(
            ['patient_id' => $sneha->id],
            [
                'presenting_complaints' => ['Exhaustion', 'Impostor feelings', 'Panic sensations during presentations'],
                'hopi' => 'Software engineer experiencing severe anxiety after recent team lead promotion.',
                'family_history' => ['Father' => 'Retired', 'Mother' => 'Homemaker', 'Siblings' => 'None'],
                'personal_history' => ['Education' => 'B.Tech', 'Social' => 'Close circle of friends', 'Substance Use' => 'Caffeine excess'],
                'last_updated_date' => Carbon::create(2025, 9, 28),
            ]
        );

        ClinicalImpression::updateOrCreate(
            ['patient_id' => $sneha->id],
            [
                'primary_concerns' => ['Work Burnout', 'Panic Symptoms', 'Boundary Setting'],
                'provisional_diagnosis' => 'Panic Disorder with Agoraphobia Features',
            ]
        );

        Appointment::updateOrCreate(
            ['patient_id' => $sneha->id, 'session_number' => 3],
            [
                'user_id' => $doctor->id,
                'scheduled_at' => $today->copy()->setTime(15, 0),
                'duration_minutes' => 45,
                'session_type' => 'Cognitive Reframing Session',
                'status' => 'scheduled',
            ]
        );

        // 5. Patient 4: Kabir Khan (P004) - 8 / M
        $kabir = Patient::updateOrCreate(
            ['patient_code' => 'P004'],
            [
                'name' => 'Kabir Khan',
                'age' => 8,
                'gender' => 'M',
                'phone' => '9876543213',
                'registration_date' => Carbon::create(2025, 9, 20),
                'status' => 'follow_up',
                'notes' => 'Separation anxiety when transitioning to school bus.',
            ]
        );

        CaseHistory::updateOrCreate(
            ['patient_id' => $kabir->id],
            [
                'presenting_complaints' => ['Crying upon morning separation', 'Clinging to mother', 'Refusal to enter classroom'],
                'hopi' => 'Onset after 2-week school absence due to viral fever.',
                'family_history' => ['Both parents supportive and engaged.'],
                'personal_history' => ['Development' => 'Normal milestones', 'Education' => '3rd Grade'],
                'last_updated_date' => Carbon::create(2025, 9, 26),
            ]
        );

        ClinicalImpression::updateOrCreate(
            ['patient_id' => $kabir->id],
            [
                'primary_concerns' => ['Separation Anxiety', 'School Transition'],
                'provisional_diagnosis' => 'Separation Anxiety Disorder of Childhood',
            ]
        );

        // 6. Patient 5: Tania Mondal (P005) - 35 / F
        $tania = Patient::updateOrCreate(
            ['patient_code' => 'P005'],
            [
                'name' => 'Tania Mondal',
                'age' => 35,
                'gender' => 'F',
                'phone' => '9876543214',
                'registration_date' => Carbon::create(2025, 8, 20),
                'status' => 'completed',
                'notes' => 'Successful bereavement counseling program completed.',
            ]
        );

        CaseHistory::updateOrCreate(
            ['patient_id' => $tania->id],
            [
                'presenting_complaints' => ['Prolonged grief', 'Sleep disturbances', 'Appetite reduction'],
                'hopi' => 'Loss of parent 8 months prior, responded positively to 6-session grief processing.',
                'family_history' => ['Married, one child.'],
                'personal_history' => ['Education' => 'Postgraduate', 'Social' => 'Supportive partner'],
                'last_updated_date' => Carbon::create(2025, 9, 25),
            ]
        );

        ClinicalImpression::updateOrCreate(
            ['patient_id' => $tania->id],
            [
                'primary_concerns' => ['Grief Processing', 'Emotional Acceptance'],
                'provisional_diagnosis' => 'Adjustment Disorder with Depressed Mood (Resolved)',
            ]
        );

        // Create remaining sample patients to total 24 patients (matching screenshot stats: 24 total, 18 assigned, 12 completed, 6 in progress)
        for ($i = 6; $i <= 24; $i++) {
            $code = 'P'.str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $gender = $i % 2 === 0 ? 'F' : 'M';
            $status = $i <= 12 ? 'completed' : ($i <= 18 ? 'in_progress' : ($i <= 21 ? 'follow_up' : 'inactive'));

            $p = Patient::firstOrCreate(
                ['patient_code' => $code],
                [
                    'name' => "Clinic Patient {$i}",
                    'age' => rand(7, 60),
                    'gender' => $gender,
                    'phone' => '98765'.str_pad((string) (43210 + $i), 5, '0', STR_PAD_LEFT),
                    'email' => "patient{$i}@example.com",
                    'registration_date' => Carbon::now()->subDays($i * 4),
                    'status' => $status,
                ]
            );

            ClinicalImpression::firstOrCreate(
                ['patient_id' => $p->id],
                [
                    'primary_concerns' => ['Stress', 'Wellness'],
                    'provisional_diagnosis' => 'Psychological Consultation',
                ]
            );
        }
    }
}
