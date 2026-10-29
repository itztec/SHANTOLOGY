@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-6 h-6 text-slate-700 dark:text-slate-300"></i>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Patient Directory</h1>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage and access all patient clinical records, demographics, and case files.</p>
        </div>

        <a href="{{ route('patients.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0d5964] hover:bg-[#0a464f] text-white font-bold text-xs rounded-xl shadow-xs transition-colors self-start md:self-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add New Patient</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-2 md:pb-0 text-xs font-semibold">
            <a href="{{ route('patients.index', ['status' => 'all', 'search' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg transition-colors {{ $currentStatus === 'all' ? 'bg-teal-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                All Patients
            </a>
            <a href="{{ route('patients.index', ['status' => 'in_progress', 'search' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg transition-colors {{ $currentStatus === 'in_progress' ? 'bg-teal-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                In Progress
            </a>
            <a href="{{ route('patients.index', ['status' => 'completed', 'search' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg transition-colors {{ $currentStatus === 'completed' ? 'bg-teal-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                Completed
            </a>
            <a href="{{ route('patients.index', ['status' => 'follow_up', 'search' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg transition-colors {{ $currentStatus === 'follow_up' ? 'bg-teal-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                Follow Up
            </a>
        </div>

        <!-- Directory Search Form -->
        <form method="GET" action="{{ route('patients.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $currentStatus }}">
            <div class="relative w-full md:w-64">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Filter by name, ID, phone..." 
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-[#182a34] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#1e3440] focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            @if($search)
                <a href="{{ route('patients.index', ['status' => $currentStatus]) }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Patients Table Card -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-[#0f1b21] text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-5 py-3">Patient ID</th>
                        <th class="px-5 py-3">Patient Name</th>
                        <th class="px-5 py-3">Age / Gender</th>
                        <th class="px-5 py-3">Phone</th>
                        <th class="px-5 py-3">Registered Date</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-bold text-teal-700 dark:text-teal-400">
                                {{ $patient->patient_code }}
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('patients.show', $patient) }}" class="font-bold text-slate-800 dark:text-slate-100 hover:text-teal-700 dark:hover:text-teal-400 flex items-center gap-2">
                                    <span>{{ $patient->name }}</span>
                                </a>
                                @if($patient->guardian_name)
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">Guardian: {{ $patient->guardian_name }} ({{ $patient->guardian_relation ?? 'Parent' }})</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-600 dark:text-slate-300">
                                {{ $patient->age }} / {{ $patient->gender }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-600 dark:text-slate-300">
                                {{ $patient->phone }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                                {{ $patient->registration_date->format('d-m-Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($patient->status === 'in_progress')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/60">
                                        In Progress
                                    </span>
                                @elseif($patient->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Completed
                                    </span>
                                @elseif($patient->status === 'follow_up')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60">
                                        Follow Up
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('patients.show', $patient) }}" 
                                       class="px-3 py-1 bg-white dark:bg-[#182a34] hover:bg-teal-50 dark:hover:bg-teal-900/40 text-teal-700 dark:text-teal-300 font-semibold text-xs border border-teal-200 dark:border-teal-700/60 rounded-lg shadow-2xs transition-colors">
                                        Clinical File
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Edit Demographics">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                                <i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-sm font-semibold">No patients found.</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Try clearing filters or adding a new patient.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
