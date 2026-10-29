<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * Download comprehensive clinic database backup in JSON format.
     */
    public function download(): StreamedResponse
    {
        $backupData = [
            'app' => 'SHANTOLOGY Psychology Clinic',
            'exported_at' => now()->toIso8601String(),
            'clinicians' => User::all(),
            'patients' => Patient::with([
                'caseHistory',
                'clinicalImpression',
                'mentalStatusExaminations',
                'assessments',
                'goalPlans',
                'progressReports',
                'appointments',
            ])->get(),
        ];

        $json = json_encode($backupData, JSON_PRETTY_PRINT);
        $fileName = 'shantology_backup_'.now()->format('Y_m_d_His').'.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $fileName, [
            'Content-Type' => 'application/json',
        ]);
    }
}
