<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ClinicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication & Landing Portal
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.direct');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Clinical EMR Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients Directory & Profiles
    Route::resource('patients', PatientController::class);

    // Clinical EMR Actions for Patient
    Route::prefix('patients/{patient}')->name('clinical.')->group(function () {
        Route::put('/case-history', [ClinicalRecordController::class, 'updateCaseHistory'])->name('case-history.update');
        Route::post('/mse', [ClinicalRecordController::class, 'storeMse'])->name('mse.store');
        Route::post('/assessments', [ClinicalRecordController::class, 'storeAssessment'])->name('assessment.store');
        Route::put('/impression', [ClinicalRecordController::class, 'updateImpression'])->name('impression.update');
        Route::post('/goals', [ClinicalRecordController::class, 'storeGoal'])->name('goals.store');
        Route::put('/goals/{goal}', [ClinicalRecordController::class, 'updateGoal'])->name('goals.update');
        Route::post('/progress-reports', [ClinicalRecordController::class, 'storeProgressReport'])->name('progress.store');
    });

    // Appointments & Calendar
    Route::resource('appointments', AppointmentController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/calendar', [AppointmentController::class, 'index'])->name('calendar.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/patients/{patient}', [ReportController::class, 'patientSummary'])->name('reports.patient-summary');

    // Backup Export
    Route::get('/backup/download', [BackupController::class, 'download'])->name('backup.download');

    // Universal Quick Search JSON endpoint
    Route::get('/quick-search', function (Request $request) {
        $term = $request->query('q');
        if (! $term || strlen($term) < 1) {
            return response()->json([]);
        }

        $patients = Patient::search($term)
            ->select(['id', 'patient_code', 'name', 'phone', 'age', 'gender', 'status'])
            ->take(6)
            ->get();

        return response()->json($patients);
    })->name('quick-search');
});
