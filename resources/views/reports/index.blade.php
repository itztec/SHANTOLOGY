@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-6 h-6 text-slate-700 dark:text-slate-300"></i>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Clinical Reports & Documentation</h1>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Export official case summaries, review practice caseload statistics, and manage data backups.</p>
        </div>

        <a href="{{ route('backup.download') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition-colors self-start md:self-auto">
            <i data-lucide="database" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
            <span>Export Database Backup (.JSON)</span>
        </a>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Active Practice</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $totalPatients }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Registered clients</div>
        </div>

        <div class="bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">In Progress Cases</div>
            <div class="text-3xl font-black text-sky-600 dark:text-sky-400 mt-1">{{ $inProgressCases }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Active therapy cycles</div>
        </div>

        <div class="bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Completed Cases</div>
            <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $completedCases }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Discharged / Goals met</div>
        </div>

        <div class="bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Assessments Logged</div>
            <div class="text-3xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ $totalAssessments }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $completedAssessments }} scored</div>
        </div>
    </div>

    <!-- Patient Reports Generator Table -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="file-check" class="w-5 h-5 text-teal-600 dark:text-teal-400"></i>
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Generate Individual Clinical Case Reports</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-[#0f1b21] text-[11px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Primary Diagnosis / Concern</th>
                        <th class="px-5 py-3">Registered Date</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Report Format</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($patients as $patient)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('patients.show', $patient) }}" class="font-bold text-slate-800 dark:text-slate-100 hover:text-teal-700 dark:hover:text-teal-400">
                                    {{ $patient->name }}
                                </a>
                                <div class="text-xs font-mono text-teal-600 dark:text-teal-400">{{ $patient->patient_code }} · {{ $patient->age }}/{{ $patient->gender }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs">
                                <div class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $patient->clinicalImpression?->provisional_diagnosis ?? 'Psychological Consultation' }}
                                </div>
                                <div class="text-slate-400 dark:text-slate-400">
                                    {{ implode(', ', $patient->clinicalImpression?->primary_concerns ?? ['General Counseling']) }}
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                                {{ $patient->registration_date->format('d-m-Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $patient->status === 'completed' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300' : 'bg-sky-50 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300' }}">
                                    {{ ucwords(str_replace('_', ' ', $patient->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('reports.patient-summary', $patient) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 dark:bg-teal-950/80 hover:bg-teal-100 dark:hover:bg-teal-900/60 text-teal-800 dark:text-teal-200 font-bold text-xs rounded-xl border border-teal-200 dark:border-teal-800/60 transition-colors">
                                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                    <span>Print / PDF Summary</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
