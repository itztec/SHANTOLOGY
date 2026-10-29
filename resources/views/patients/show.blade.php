@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ 
    activeTab: '{{ request()->query('tab', 'case_history') }}',
    editCaseHistoryModal: false,
    newMseModal: false,
    newAssessmentModal: false,
    editImpressionModal: false,
    newGoalModal: false,
    newSessionModal: false
}">

    <!-- Top Navigation Breadcrumb & Patient Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-700 dark:hover:text-teal-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Patient List</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-[#122027] hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-xs border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs transition-colors">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <span>Edit Patient</span>
            </a>

            <a href="{{ route('reports.patient-summary', $patient) }}" target="_blank" class="p-2 bg-white dark:bg-[#122027] hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs transition-colors" title="Print Case Summary">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- Patient Header Card (From Screenshot 2) -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-6 transition-colors">
        <!-- Patient Core Identity -->
        <div class="flex items-center gap-5">
            <!-- Illustrated Avatar -->
            <div class="w-20 h-20 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border-2 border-teal-200/60 dark:border-teal-800/60 flex items-center justify-center overflow-hidden shrink-0">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ urlencode($patient->name) }}&backgroundColor=b6e3f4,c0aede,d1d4f9" 
                     alt="{{ $patient->name }}" 
                     class="w-full h-full object-cover">
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $patient->name }}</h1>
                    <span class="text-xs font-mono font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/80 px-2 py-0.5 rounded-md border border-teal-100 dark:border-teal-800/80">
                        Patient ID: {{ $patient->patient_code }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-1">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Age/Gender: <strong class="text-slate-700 dark:text-slate-200">{{ $patient->age }} / {{ $patient->gender }}</strong></span>
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Phone: <strong class="font-mono text-slate-700 dark:text-slate-200">{{ $patient->phone }}</strong></span>
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Date of Registration: <strong class="text-slate-700 dark:text-slate-200">{{ $patient->registration_date->format('d-m-Y') }}</strong></span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Side Metadata Badges -->
        <div class="flex flex-wrap items-center gap-4 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-slate-800 pt-4 lg:pt-0 lg:pl-6">
            <!-- Current Status -->
            <div class="bg-slate-50 dark:bg-[#162730] border border-slate-200/70 dark:border-slate-800 rounded-xl p-3 min-w-[130px]">
                <div class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i data-lucide="activity" class="w-3 h-3 text-sky-500"></i>
                    <span>Current Status</span>
                </div>
                <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $patient->status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300' : ($patient->status === 'in_progress' ? 'bg-sky-100 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300' : 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300') }}">
                        {{ ucwords(str_replace('_', ' ', $patient->status)) }}
                    </span>
                </div>
            </div>

            <!-- Last Visit -->
            <div class="bg-slate-50 dark:bg-[#162730] border border-slate-200/70 dark:border-slate-800 rounded-xl p-3 min-w-[130px]">
                <div class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i data-lucide="calendar-check" class="w-3 h-3 text-slate-400"></i>
                    <span>Last Visit</span>
                </div>
                <div class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-1">
                    {{ $patient->last_visit ? $patient->last_visit->scheduled_at->format('d-m-Y') : $patient->registration_date->format('d-m-Y') }}
                </div>
            </div>

            <!-- Next Appointment -->
            <div class="bg-slate-50 dark:bg-[#162730] border border-slate-200/70 dark:border-slate-800 rounded-xl p-3 min-w-[130px]">
                <div class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i data-lucide="calendar-clock" class="w-3 h-3 text-teal-600 dark:text-teal-400"></i>
                    <span>Next Appointment</span>
                </div>
                <div class="text-xs font-bold text-teal-700 dark:text-teal-300 mt-1">
                    {{ $patient->next_appointment ? $patient->next_appointment->scheduled_at->format('d-m-Y') : '15-11-2025' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical Sub-Tab Navigation Bar (Matching Screenshot 2) -->
    <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#122027] rounded-2xl p-1.5 shadow-2xs flex overflow-x-auto space-x-1 text-sm font-semibold transition-colors">
        <!-- 1. Case History -->
        <button type="button" 
                @click="activeTab = 'case_history'"
                :class="activeTab === 'case_history' ? 'bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border-b-2 border-teal-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="file-text" class="w-4 h-4 text-teal-600 dark:text-teal-400"></i>
            <span>Case History</span>
        </button>

        <!-- 2. MSE -->
        <button type="button" 
                @click="activeTab = 'mse'"
                :class="activeTab === 'mse' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300 border-b-2 border-sky-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="brain" class="w-4 h-4 text-sky-600 dark:text-sky-400"></i>
            <span>MSE</span>
        </button>

        <!-- 3. Assessment -->
        <button type="button" 
                @click="activeTab = 'assessment'"
                :class="activeTab === 'assessment' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-b-2 border-emerald-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="clipboard-check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
            <span>Assessment</span>
        </button>

        <!-- 4. Clinical Impression -->
        <button type="button" 
                @click="activeTab = 'impression'"
                :class="activeTab === 'impression' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-b-2 border-amber-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="stethoscope" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
            <span>Clinical Impression</span>
        </button>

        <!-- 5. Goal Plan -->
        <button type="button" 
                @click="activeTab = 'goals'"
                :class="activeTab === 'goals' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-b-2 border-rose-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="target" class="w-4 h-4 text-rose-600 dark:text-rose-400"></i>
            <span>Goal Plan</span>
        </button>

        <!-- 6. Progress Report -->
        <button type="button" 
                @click="activeTab = 'progress'"
                :class="activeTab === 'progress' ? 'bg-cyan-50 dark:bg-cyan-950/60 text-cyan-800 dark:text-cyan-300 border-b-2 border-cyan-600 shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all whitespace-nowrap cursor-pointer">
            <i data-lucide="trending-up" class="w-4 h-4 text-cyan-600 dark:text-cyan-400"></i>
            <span>Progress Report</span>
        </button>
    </div>

    <!-- MAIN TWO-COLUMN WORK AREA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT 2/3 COLUMN: Active Tab Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- TAB 1: CASE HISTORY -->
            <div x-show="activeTab === 'case_history'" class="space-y-6">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="file-text" class="w-5 h-5 text-teal-600 dark:text-teal-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Case History</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                                Last Updated: {{ $patient->caseHistory?->last_updated_date ? $patient->caseHistory->last_updated_date->format('d-m-Y') : '01-10-2025' }}
                            </span>
                            <button @click="editCaseHistoryModal = true" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-teal-700 dark:hover:text-teal-300 transition-colors cursor-pointer" title="Edit Case History">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 1. Presenting Complaints -->
                    <div class="mt-6 bg-pink-50/40 dark:bg-pink-950/20 border border-pink-100/80 dark:border-pink-900/40 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-pink-900 dark:text-pink-300 flex items-center gap-2 mb-3">
                            <span class="w-5 h-5 rounded-full bg-pink-200 dark:bg-pink-900/80 text-pink-800 dark:text-pink-200 text-xs flex items-center justify-center font-bold">1</span>
                            <span>Presenting Complaints</span>
                        </h3>
                        <ul class="space-y-2 text-sm text-slate-700 dark:text-slate-200 font-medium">
                            @if($patient->caseHistory && !empty($patient->caseHistory->presenting_complaints))
                                @foreach($patient->caseHistory->presenting_complaints as $complaint)
                                    <li class="flex items-start gap-2.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500 dark:bg-pink-400 mt-2 shrink-0"></span>
                                        <span>{{ $complaint }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-start gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500 dark:bg-pink-400 mt-2 shrink-0"></span>
                                    <span>Difficulty in concentrating in school</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500 dark:bg-pink-400 mt-2 shrink-0"></span>
                                    <span>Often feels anxious and worries about small things</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500 dark:bg-pink-400 mt-2 shrink-0"></span>
                                    <span>Frequent mood changes and irritability</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- 2. History of Present Illness (HOPI) -->
                    <div class="mt-5 bg-blue-50/40 dark:bg-blue-950/20 border border-blue-100/80 dark:border-blue-900/40 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-blue-900 dark:text-blue-300 flex items-center gap-2 mb-3">
                            <span class="w-5 h-5 rounded-full bg-blue-200 dark:bg-blue-900/80 text-blue-800 dark:text-blue-200 text-xs flex items-center justify-center font-bold">2</span>
                            <span>History of Present Illness (HOPI)</span>
                        </h3>
                        <p class="text-sm text-slate-700 dark:text-slate-200 leading-relaxed font-normal">
                            {{ $patient->caseHistory?->hopi ?? 'Symptoms started around 6 months back, initially noticed by parents as increased anxiety and reduced interest in studies. The child also reports difficulty in sleep and occasional headache. No history of self-harm or suicidal ideation.' }}
                        </p>
                    </div>

                    <!-- 3 & 4. Family History & Personal History Side-by-Side -->
                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Family History -->
                        <div class="bg-purple-50/40 dark:bg-purple-950/20 border border-purple-100/80 dark:border-purple-900/40 rounded-2xl p-5">
                            <h3 class="text-sm font-bold text-purple-900 dark:text-purple-300 flex items-center gap-2 mb-3">
                                <span class="w-5 h-5 rounded-full bg-purple-200 dark:bg-purple-900/80 text-purple-800 dark:text-purple-200 text-xs flex items-center justify-center font-bold">3</span>
                                <span>Family History</span>
                            </h3>
                            <ul class="space-y-2 text-xs text-slate-700 dark:text-slate-300">
                                @php $fam = $patient->caseHistory?->family_history ?? []; @endphp
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[70px]">Father:</span>
                                    <span>{{ $fam['Father'] ?? 'No significant mental illness' }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[70px]">Mother:</span>
                                    <span>{{ $fam['Mother'] ?? 'History of anxiety (on treatment)' }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[70px]">Siblings:</span>
                                    <span>{{ $fam['Siblings'] ?? 'Younger brother (healthy)' }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Personal History -->
                        <div class="bg-emerald-50/40 dark:bg-emerald-950/20 border border-emerald-100/80 dark:border-emerald-900/40 rounded-2xl p-5">
                            <h3 class="text-sm font-bold text-emerald-900 dark:text-emerald-300 flex items-center gap-2 mb-3">
                                <span class="w-5 h-5 rounded-full bg-emerald-200 dark:bg-emerald-900/80 text-emerald-800 dark:text-emerald-200 text-xs flex items-center justify-center font-bold">4</span>
                                <span>Personal History</span>
                            </h3>
                            <ul class="space-y-2 text-xs text-slate-700 dark:text-slate-300">
                                @php $pers = $patient->caseHistory?->personal_history ?? []; @endphp
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[125px]">Birth & Development:</span>
                                    <span>{{ $pers['Birth & Development'] ?? 'Normal' }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[125px]">Education:</span>
                                    <span>{{ $pers['Education'] ?? '5th Grade' }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[125px]">Social:</span>
                                    <span>{{ $pers['Social'] ?? 'Good relationship with friends' }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 min-w-[125px]">Substance Use:</span>
                                    <span>{{ $pers['Substance Use'] ?? 'None' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: MENTAL STATUS EXAMINATION (MSE) -->
            <div x-show="activeTab === 'mse'" class="space-y-6" style="display: none;">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="brain" class="w-5 h-5 text-sky-600 dark:text-sky-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Mental Status Examination (MSE)</h2>
                        </div>
                        <button @click="newMseModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Record New MSE</span>
                        </button>
                    </div>

                    @php $latestMse = $patient->mentalStatusExaminations->first(); @endphp
                    @if($latestMse)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">General Appearance & Behavior</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->general_appearance ?? 'Well groomed, cooperative, good eye contact.' }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">Psychomotor Activity</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->psychomotor_behavior ?? 'Mild fidgeting noted during anxiety provoking topics.' }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">Speech & Language</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->speech ?? 'Normal rate, volume, and rhythm; coherent and goal-directed.' }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">Mood and Affect</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->mood_and_affect ?? 'Anxious mood, affect reactive and congruent.' }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">Thought Process & Content</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->thought_content ?? 'Preoccupied with test performance. No suicidal ideation.' }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/60 dark:border-slate-800">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-wider mb-1">Insight & Judgment</span>
                                <p class="text-slate-600 dark:text-slate-300">{{ $latestMse->insight_and_judgment ?? 'Fair insight, age-appropriate judgment.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400 dark:text-slate-500 text-sm">
                            No Mental Status Examination recorded yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- TAB 3: PSYCHOMETRIC ASSESSMENTS -->
            <div x-show="activeTab === 'assessment'" class="space-y-6" style="display: none;">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="clipboard-check" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Psychometric Assessments</h2>
                        </div>
                        <button @click="newAssessmentModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Add Assessment</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        @forelse($patient->assessments as $test)
                            <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-[#162730] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $test->assessment_name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Date: {{ $test->assessment_date->format('d-m-Y') }} · Category: {{ $test->category ?? 'Clinical' }}
                                    </div>
                                    @if($test->interpretation)
                                        <div class="text-xs text-slate-600 dark:text-slate-300 mt-2 font-medium bg-white dark:bg-[#122027] p-2 rounded-lg border border-slate-100 dark:border-slate-800">
                                            Interpretation: {{ $test->interpretation }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-xs font-bold font-mono text-emerald-700 dark:text-emerald-300 bg-emerald-100/80 dark:bg-emerald-950/80 px-2.5 py-1 rounded-md inline-block border border-emerald-200/40 dark:border-emerald-800/60">
                                        {{ $test->score ?? 'Pending' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500">{{ $test->status }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400 dark:text-slate-500 text-sm">
                                No assessment records logged for this patient.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 4: CLINICAL IMPRESSION -->
            <div x-show="activeTab === 'impression'" class="space-y-6" style="display: none;">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="stethoscope" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Clinical Impression & Formulation</h2>
                        </div>
                        <button @click="editImpressionModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>Update Impression</span>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Primary Concerns</span>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if($patient->clinicalImpression && !empty($patient->clinicalImpression->primary_concerns))
                                    @foreach($patient->clinicalImpression->primary_concerns as $concern)
                                        <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-lg text-xs font-bold">
                                            {{ $concern }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-lg text-xs font-bold">Anxiety</span>
                                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-lg text-xs font-bold">Attention Issues</span>
                                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-lg text-xs font-bold">Mood Changes</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/80 dark:border-slate-800">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-1">Provisional Diagnosis</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-100">
                                {{ $patient->clinicalImpression?->provisional_diagnosis ?? 'To be determined' }}
                            </div>
                            @if($patient->clinicalImpression?->icd_dsm_code)
                                <div class="text-xs font-mono text-teal-600 dark:text-teal-400 mt-1">
                                    {{ $patient->clinicalImpression->icd_dsm_code }}
                                </div>
                            @endif
                        </div>

                        @if($patient->clinicalImpression?->case_formulation)
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#162730] border border-slate-200/80 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-1">Biopsychosocial Formulation</span>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                    {{ $patient->clinicalImpression->case_formulation }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 5: GOAL PLAN -->
            <div x-show="activeTab === 'goals'" class="space-y-6" style="display: none;">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="target" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Treatment & Therapy Goals</h2>
                        </div>
                        <button @click="newGoalModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Add Goal</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        @forelse($patient->goalPlans as $goal)
                            <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-[#162730] flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider {{ $goal->goal_type === 'short_term' ? 'bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300' : 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300' }}">
                                            {{ str_replace('_', ' ', $goal->goal_type) }}
                                        </span>
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $goal->title }}</span>
                                    </div>
                                    @if($goal->description)
                                        <p class="text-xs text-slate-600 dark:text-slate-300">{{ $goal->description }}</p>
                                    @endif
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Modality: <strong class="text-slate-600 dark:text-slate-300">{{ $goal->intervention_modality ?? 'CBT' }}</strong>
                                        @if($goal->target_date) · Target: {{ $goal->target_date->format('d-m-Y') }} @endif
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('clinical.goals.update', ['patient' => $patient, 'goal' => $goal]) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border-slate-200 dark:border-slate-700 py-1 px-2.5 focus:ring-teal-500 {{ $goal->status === 'achieved' ? 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300' : ($goal->status === 'in_progress' ? 'bg-amber-50 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300') }}">
                                        <option value="pending" {{ $goal->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $goal->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="achieved" {{ $goal->status === 'achieved' ? 'selected' : '' }}>Achieved</option>
                                    </select>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400 dark:text-slate-500 text-sm">
                                No therapy goals defined yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 6: PROGRESS REPORT (SOAP NOTES) -->
            <div x-show="activeTab === 'progress'" class="space-y-6" style="display: none;">
                <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="trending-up" class="w-5 h-5 text-cyan-600 dark:text-cyan-400"></i>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Session Progress Reports</h2>
                        </div>
                        <button @click="newSessionModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Log Session Note</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($patient->progressReports as $report)
                            <div class="p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-[#162730] space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950/80 text-teal-800 dark:text-teal-300 text-xs font-bold flex items-center justify-center font-mono border border-teal-200/40 dark:border-teal-800/60">
                                            S{{ $report->session_number }}
                                        </span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $report->session_title ?? "Session {$report->session_number}" }}</span>
                                    </div>
                                    <span class="text-xs font-mono text-slate-400 dark:text-slate-500">{{ $report->session_date->format('d-m-Y') }}</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    @if($report->subjective)
                                        <div class="bg-white dark:bg-[#122027] p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                            <span class="font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-0.5">Subjective (S)</span>
                                            <p class="text-slate-700 dark:text-slate-300">{{ $report->subjective }}</p>
                                        </div>
                                    @endif
                                    @if($report->objective)
                                        <div class="bg-white dark:bg-[#122027] p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                            <span class="font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-0.5">Objective (O)</span>
                                            <p class="text-slate-700 dark:text-slate-300">{{ $report->objective }}</p>
                                        </div>
                                    @endif
                                    @if($report->assessment)
                                        <div class="bg-white dark:bg-[#122027] p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                            <span class="font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-0.5">Assessment (A)</span>
                                            <p class="text-slate-700 dark:text-slate-300">{{ $report->assessment }}</p>
                                        </div>
                                    @endif
                                    @if($report->plan)
                                        <div class="bg-white dark:bg-[#122027] p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                            <span class="font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-0.5">Plan (P)</span>
                                            <p class="text-slate-700 dark:text-slate-300">{{ $report->plan }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($report->homework)
                                    <div class="bg-teal-50/60 dark:bg-teal-950/40 p-2.5 rounded-xl border border-teal-100 dark:border-teal-900/60 text-xs text-teal-900 dark:text-teal-200 flex items-center gap-2">
                                        <i data-lucide="sparkles" class="w-4 h-4 text-teal-600 dark:text-teal-400 shrink-0"></i>
                                        <span><strong>Homework Assigned:</strong> {{ $report->homework }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400 dark:text-slate-500 text-sm">
                                No session progress reports logged yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT 1/3 COLUMN: Quick Actions & Clinical Summary (From Screenshot 2) -->
        <div class="space-y-6">

            <!-- Quick Actions Grid (6 Color-Coded Tiles) -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="zap" class="w-4 h-4 text-slate-700 dark:text-slate-300"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- 1. Case History -->
                    <button type="button" @click="activeTab = 'case_history'" class="p-3.5 rounded-xl bg-purple-50/70 dark:bg-purple-950/30 border border-purple-100 dark:border-purple-900/50 hover:bg-purple-100/70 dark:hover:bg-purple-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-purple-700 dark:text-purple-300 mb-1">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">Case History</span>
                        </div>
                        <div class="text-[11px] text-purple-600/80 dark:text-purple-400 font-medium">View / Edit</div>
                    </button>

                    <!-- 2. MSE -->
                    <button type="button" @click="activeTab = 'mse'" class="p-3.5 rounded-xl bg-sky-50/70 dark:bg-sky-950/30 border border-sky-100 dark:border-sky-900/50 hover:bg-sky-100/70 dark:hover:bg-sky-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-sky-700 dark:text-sky-300 mb-1">
                            <i data-lucide="brain" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">MSE</span>
                        </div>
                        <div class="text-[11px] text-sky-600/80 dark:text-sky-400 font-medium">View / Edit</div>
                    </button>

                    <!-- 3. Assessment -->
                    <button type="button" @click="activeTab = 'assessment'" class="p-3.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 hover:bg-emerald-100/70 dark:hover:bg-emerald-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-300 mb-1">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">Assessment</span>
                        </div>
                        <div class="text-[11px] text-emerald-600/80 dark:text-emerald-400 font-medium">View / Add</div>
                    </button>

                    <!-- 4. Clinical Impression -->
                    <button type="button" @click="activeTab = 'impression'" class="p-3.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900/50 hover:bg-amber-100/70 dark:hover:bg-amber-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-amber-700 dark:text-amber-300 mb-1">
                            <i data-lucide="stethoscope" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">Impression</span>
                        </div>
                        <div class="text-[11px] text-amber-600/80 dark:text-amber-400 font-medium">View / Edit</div>
                    </button>

                    <!-- 5. Goal Plan -->
                    <button type="button" @click="activeTab = 'goals'" class="p-3.5 rounded-xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/50 hover:bg-rose-100/70 dark:hover:bg-rose-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-rose-700 dark:text-rose-300 mb-1">
                            <i data-lucide="target" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">Goal Plan</span>
                        </div>
                        <div class="text-[11px] text-rose-600/80 dark:text-rose-400 font-medium">View / Edit</div>
                    </button>

                    <!-- 6. Progress Report -->
                    <button type="button" @click="activeTab = 'progress'" class="p-3.5 rounded-xl bg-cyan-50/70 dark:bg-cyan-950/30 border border-cyan-100 dark:border-cyan-900/50 hover:bg-cyan-100/70 dark:hover:bg-cyan-950/50 transition-colors text-left group cursor-pointer">
                        <div class="flex items-center gap-2 text-cyan-700 dark:text-cyan-300 mb-1">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                            <span class="text-xs font-bold">Progress</span>
                        </div>
                        <div class="text-[11px] text-cyan-600/80 dark:text-cyan-400 font-medium">View / Add</div>
                    </button>
                </div>
            </div>

            <!-- Clinical Summary Card (From Screenshot 2) -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-amber-500"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Clinical Summary</h3>
                </div>

                <!-- Primary Concerns Tags -->
                <div>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-2">Primary Concerns:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @if($patient->clinicalImpression && !empty($patient->clinicalImpression->primary_concerns))
                            @foreach($patient->clinicalImpression->primary_concerns as $concern)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 border border-sky-100 dark:border-sky-800/80">
                                    {{ $concern }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 border border-sky-100 dark:border-sky-800/80">Anxiety</span>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 border border-sky-100 dark:border-sky-800/80">Attention Issues</span>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 dark:bg-sky-950/80 text-sky-800 dark:text-sky-300 border border-sky-100 dark:border-sky-800/80">Mood Changes</span>
                        @endif
                    </div>
                </div>

                <!-- Provisional Diagnosis -->
                <div>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider block mb-1">Provisional Diagnosis:</span>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                        {{ $patient->clinicalImpression?->provisional_diagnosis ?? 'To be determined' }}
                    </p>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL 1: EDIT CASE HISTORY -->
    <div x-show="editCaseHistoryModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="editCaseHistoryModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Edit Clinical Case History</h3>
                    <button @click="editCaseHistoryModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.case-history.update', $patient) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Presenting Complaints (one per line)</label>
                        <textarea name="presenting_complaints" rows="3" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100 focus:ring-teal-500">{{ implode("\n", $patient->caseHistory?->presenting_complaints ?? []) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">History of Present Illness (HOPI)</label>
                        <textarea name="hopi" rows="4" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100 focus:ring-teal-500">{{ $patient->caseHistory?->hopi }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Father's History</label>
                            <input type="text" name="father_history" value="{{ $patient->caseHistory?->family_history['Father'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Mother's History</label>
                            <input type="text" name="mother_history" value="{{ $patient->caseHistory?->family_history['Mother'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Education</label>
                            <input type="text" name="education" value="{{ $patient->caseHistory?->personal_history['Education'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Social Relations</label>
                            <input type="text" name="social" value="{{ $patient->caseHistory?->personal_history['Social'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="editCaseHistoryModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-xs font-semibold rounded-xl hover:bg-teal-700 cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: RECORD NEW MSE -->
    <div x-show="newMseModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="newMseModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Record Mental Status Examination</h3>
                    <button @click="newMseModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.mse.store', $patient) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Exam Date</label>
                        <input type="date" name="exam_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Appearance & Behavior</label>
                            <textarea name="general_appearance" rows="2" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Psychomotor Activity</label>
                            <textarea name="psychomotor_behavior" rows="2" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Mood and Affect</label>
                            <input type="text" name="mood_and_affect" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Speech</label>
                            <input type="text" name="speech" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Thought Process & Content</label>
                        <textarea name="thought_content" rows="2" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="newMseModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-sky-600 text-white text-xs font-semibold rounded-xl hover:bg-sky-700 cursor-pointer">Save MSE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: ADD ASSESSMENT -->
    <div x-show="newAssessmentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="newAssessmentModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Add Psychometric Assessment</h3>
                    <button @click="newAssessmentModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.assessment.store', $patient) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Assessment Tool Name</label>
                        <input type="text" name="assessment_name" placeholder="e.g. BDI-II, GAD-7, Vanderbilt ADHD" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Category</label>
                            <input type="text" name="category" placeholder="e.g. Mood, Cognitive" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Score / Result</label>
                            <input type="text" name="score" placeholder="e.g. 15/21 Moderate" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Date</label>
                            <input type="date" name="assessment_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Status</label>
                            <select name="status" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                                <option value="completed">Completed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="assigned">Assigned</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Interpretation</label>
                        <textarea name="interpretation" rows="2" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="newAssessmentModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-xl hover:bg-emerald-700 cursor-pointer">Save Assessment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 4: EDIT IMPRESSION -->
    <div x-show="editImpressionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="editImpressionModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Update Clinical Impression</h3>
                    <button @click="editImpressionModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.impression.update', $patient) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Primary Concerns (comma separated)</label>
                        <input type="text" name="primary_concerns" value="{{ implode(', ', $patient->clinicalImpression?->primary_concerns ?? []) }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Provisional Diagnosis</label>
                        <input type="text" name="provisional_diagnosis" value="{{ $patient->clinicalImpression?->provisional_diagnosis ?? 'To be determined' }}" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Diagnostic Code (ICD-11 / DSM-5)</label>
                        <input type="text" name="icd_dsm_code" value="{{ $patient->clinicalImpression?->icd_dsm_code ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Formulation Notes</label>
                        <textarea name="case_formulation" rows="3" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">{{ $patient->clinicalImpression?->case_formulation }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="editImpressionModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded-xl hover:bg-amber-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 5: ADD GOAL -->
    <div x-show="newGoalModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="newGoalModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Add Therapeutic Goal</h3>
                    <button @click="newGoalModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.goals.store', $patient) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Goal Type</label>
                        <select name="goal_type" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                            <option value="short_term">Short Term Goal</option>
                            <option value="long_term">Long Term Goal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Goal Title</label>
                        <input type="text" name="title" required placeholder="e.g. Master diaphragmatic breathing" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Intervention Modality</label>
                        <input type="text" name="intervention_modality" placeholder="e.g. CBT, Exposure, Mindfulness" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Target Date</label>
                            <input type="date" name="target_date" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Initial Status</label>
                            <select name="status" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                                <option value="in_progress">In Progress</option>
                                <option value="pending">Pending</option>
                                <option value="achieved">Achieved</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="newGoalModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-rose-600 text-white text-xs font-semibold rounded-xl hover:bg-rose-700 cursor-pointer">Save Goal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 6: LOG SESSION PROGRESS NOTE (SOAP) -->
    <div x-show="newSessionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs">
            <div @click.away="newSessionModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-transparent dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Log Session Progress Note (SOAP)</h3>
                    <button @click="newSessionModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('clinical.progress.store', $patient) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Session #</label>
                            <input type="number" name="session_number" value="{{ ($patient->progressReports->max('session_number') ?? 0) + 1 }}" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Session Date</label>
                            <input type="date" name="session_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Session Focus Title</label>
                        <input type="text" name="session_title" placeholder="e.g. Cognitive Reframing of Test Anxiety" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Subjective (S)</label>
                            <textarea name="subjective" rows="3" placeholder="Patient's reported feelings, symptoms..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Objective (O)</label>
                            <textarea name="objective" rows="3" placeholder="Clinician observations, behavior..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Assessment (A)</label>
                            <textarea name="assessment" rows="3" placeholder="Clinical evaluation of session progress..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Plan (P)</label>
                            <textarea name="plan" rows="3" placeholder="Next steps, intervention targets..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Homework Assigned</label>
                        <input type="text" name="homework" placeholder="e.g. Daily worry journal and 5 min grounding" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#172832] text-slate-800 dark:text-slate-100">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="newSessionModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white text-xs font-semibold rounded-xl hover:bg-cyan-700 cursor-pointer">Save Session Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
