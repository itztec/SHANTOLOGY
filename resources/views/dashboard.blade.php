@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Top Header: Title, Welcome Greeting, Date -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-slate-800 dark:text-slate-100">
                <i data-lucide="home" class="w-6 h-6 text-slate-700 dark:text-slate-300"></i>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Dashboard</h1>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Welcome back, <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $psychologist->name ?? 'Dhritiman Dey' }}</span> ! Here's an overview of your clinic.
            </p>
        </div>

        <!-- Current Date Pill -->
        <div class="flex items-center gap-2 self-start md:self-auto px-4 py-2 bg-white dark:bg-[#122027] border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-xs text-xs font-semibold text-slate-600 dark:text-slate-300">
            <i data-lucide="calendar" class="w-4 h-4 text-teal-600 dark:text-teal-400"></i>
            <span>{{ $currentDate ?? now()->format('d M Y') }}</span>
        </div>
    </div>

    <!-- 4 Primary Metric Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Patients -->
        <a href="{{ route('patients.index') }}" class="group bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/70 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-teal-300 dark:hover:border-teal-600 transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Patients</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ $stats['total_patients'] }}</div>
                <div class="text-xs font-medium text-teal-600 dark:text-teal-400 mt-1 flex items-center gap-1">
                    <span>+2 new this month</span>
                </div>
            </div>
        </a>

        <!-- 2. Assigned Cases -->
        <a href="{{ route('patients.index', ['status' => 'in_progress']) }}" class="group bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/70 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-purple-300 dark:hover:border-purple-600 transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-900/60 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Assigned Cases</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ $stats['assigned_cases'] }}</div>
                <div class="text-xs font-medium text-purple-600 dark:text-purple-400 mt-1 flex items-center gap-1">
                    <span>+3 this month</span>
                </div>
            </div>
        </a>

        <!-- 3. Completed -->
        <a href="{{ route('patients.index', ['status' => 'completed']) }}" class="group bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/70 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-600 transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-900/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Completed</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ $stats['completed'] }}</div>
                <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
                    <span>+5 this month</span>
                </div>
            </div>
        </a>

        <!-- 4. In Progress -->
        <a href="{{ route('patients.index', ['status' => 'in_progress']) }}" class="group bg-white dark:bg-[#122027] rounded-2xl p-5 border border-slate-200/70 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-amber-300 dark:hover:border-amber-600 transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-900/60 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">In Progress</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 tracking-tight">{{ $stats['in_progress'] }}</div>
                <div class="text-xs font-medium text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
                    <span>+1 this month</span>
                </div>
            </div>
        </a>
    </div>

    <!-- MAIN TWO-COLUMN LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT 2/3 COLUMN: Recent Patients & Monthly Progress -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Recent Patients Table Card -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-5 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-slate-700 dark:text-slate-300"></i>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Recent Patients</h2>
                    </div>
                    <a href="{{ route('patients.index') }}" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 flex items-center gap-1">
                        <span>View All</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-[#162730] text-[11px] font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-5 py-3 w-12 text-center">#</th>
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Age / Gender</th>
                                <th class="px-5 py-3">Phone</th>
                                <th class="px-5 py-3">Session Date</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentPatients as $index => $patient)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-5 py-3.5 text-center font-medium text-slate-400 dark:text-slate-500 text-xs">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <a href="{{ route('patients.show', $patient) }}" class="font-bold text-slate-800 dark:text-slate-100 hover:text-teal-700 dark:hover:text-teal-400">
                                            {{ $patient->name }}
                                        </a>
                                        <div class="text-[11px] text-teal-600 dark:text-teal-400 font-mono">{{ $patient->patient_code }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300 font-medium">
                                        {{ $patient->age }} / {{ $patient->gender }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300 font-mono text-xs">
                                        {{ $patient->phone }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400 text-xs">
                                        @if($patient->appointments->first())
                                            {{ $patient->appointments->first()->scheduled_at->format('d-m-Y') }}
                                        @else
                                            {{ $patient->registration_date->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($patient->status === 'in_progress')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/80">
                                                In Progress
                                            </span>
                                        @elseif($patient->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/80">
                                                Completed
                                            </span>
                                        @elseif($patient->status === 'follow_up')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/80">
                                                Follow Up
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('patients.show', $patient) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-white dark:bg-[#162730] hover:bg-teal-50 dark:hover:bg-teal-900/40 text-teal-700 dark:text-teal-300 font-semibold text-xs border border-teal-200 dark:border-teal-800 rounded-lg shadow-2xs transition-colors">
                                            <span>View</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-400 dark:text-slate-500 text-sm">
                                        No patient records available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Progress Section -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-slate-700 dark:text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Monthly Progress</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Total Assigned Assessments -->
                    <div class="bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/60 rounded-2xl p-4 flex flex-col justify-between">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300 flex items-center justify-center mb-2">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $stats['total_assessments'] }}</div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Total Assigned Assessments</div>
                        </div>
                    </div>

                    <!-- Completed Assessments -->
                    <div class="bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/60 rounded-2xl p-4 flex flex-col justify-between">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center mb-2">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $stats['completed_assessments'] }}</div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Completed Assessments</div>
                        </div>
                    </div>

                    <!-- In Progress Assessments -->
                    <div class="bg-amber-50/60 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900/60 rounded-2xl p-4 flex flex-col justify-between">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-300 flex items-center justify-center mb-2">
                            <i data-lucide="hourglass" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $stats['in_progress_assessments'] }}</div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">In Progress Assessments</div>
                        </div>
                    </div>

                    <!-- New Patients This Month -->
                    <div class="bg-purple-50/60 dark:bg-purple-950/30 border border-purple-100 dark:border-purple-900/60 rounded-2xl p-4 flex flex-col justify-between">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-600 dark:text-purple-300 flex items-center justify-center mb-2">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $stats['new_patients_month'] }}</div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">New Patients This Month</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT 1/3 COLUMN: Quick Actions, Today's Schedule, Affirmation -->
        <div class="space-y-6">

            <!-- Quick Actions Panel -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="zap" class="w-5 h-5 text-slate-700 dark:text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Quick Actions</h3>
                </div>

                <div class="space-y-2.5">
                    <!-- Add New Patient (Primary Action) -->
                    <a href="{{ route('patients.create') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#0d5964] hover:bg-[#0a464f] text-white font-bold text-sm rounded-xl shadow-sm transition-all transform active:scale-98">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Add New Patient</span>
                    </a>

                    <!-- View Calendar -->
                    <a href="{{ route('calendar.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-[#162730] hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-sm rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                        <span>View Calendar</span>
                    </a>

                    <!-- Generate Report -->
                    <a href="{{ route('reports.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-[#162730] hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-sm rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                        <span>Generate Report</span>
                    </a>

                    <!-- Download Backup -->
                    <a href="{{ route('backup.download') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-[#162730] hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-sm rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                        <i data-lucide="download" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                        <span>Download Backup</span>
                    </a>
                </div>
            </div>

            <!-- Today's Schedule Card -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-check" class="w-5 h-5 text-slate-700 dark:text-slate-300"></i>
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Today's Schedule</h3>
                    </div>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300">
                        View All
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($todaySchedule as $appt)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-100 dark:border-slate-800/80 hover:border-teal-200 dark:hover:border-teal-800 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/80 px-2.5 py-1 rounded-md font-mono border border-sky-200/40 dark:border-sky-800/60">
                                    {{ $appt->scheduled_at->format('h:i A') }}
                                </span>
                                <div>
                                    <a href="{{ route('patients.show', $appt->patient) }}" class="text-xs font-bold text-slate-800 dark:text-slate-100 hover:text-teal-700 dark:hover:text-teal-400">
                                        {{ $appt->patient->name }}
                                    </a>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                        (Session {{ $appt->session_number }})
                                    </div>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                            No appointments scheduled for today.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Affirmation Quote Card -->
            <div class="bg-emerald-50/70 dark:bg-[#0c2423] border border-emerald-100 dark:border-emerald-900/60 rounded-2xl p-5 relative overflow-hidden">
                <div class="text-teal-700/60 dark:text-teal-400/50 font-serif text-3xl font-bold leading-none mb-2">“</div>
                <p class="text-sm font-semibold text-teal-900 dark:text-teal-200 italic leading-relaxed">
                    Small steps in therapy lead to big changes in life.
                </p>
                <div class="mt-4 flex items-center justify-end text-teal-600/70 dark:text-teal-400/60">
                    <i data-lucide="sprout" class="w-6 h-6"></i>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
