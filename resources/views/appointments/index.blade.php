@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ newAppointmentModal: false }">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-6 h-6 text-slate-700 dark:text-slate-300"></i>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Clinical Calendar & Sessions</h1>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Schedule therapy consultations, intake reviews, and track daily appointment slots.</p>
        </div>

        <button @click="newAppointmentModal = true" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0d5964] hover:bg-[#0a464f] text-white font-bold text-xs rounded-xl shadow-xs transition-colors self-start md:self-auto cursor-pointer">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Schedule New Session</span>
        </button>
    </div>

    <!-- Calendar Layout: 2 Columns (Agenda Timeline & Quick Booking) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Appointments List (2/3) -->
        <div class="lg:col-span-2 bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-teal-600 dark:text-teal-400"></i>
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">All Scheduled Consultations ({{ $appointments->count() }})</h2>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($appointments as $appt)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                        <div class="flex items-start gap-4">
                            <!-- Time Badge -->
                            <div class="w-16 h-14 rounded-xl bg-teal-50 dark:bg-teal-950/80 border border-teal-100 dark:border-teal-800/60 flex flex-col items-center justify-center shrink-0">
                                <span class="text-xs font-bold font-mono text-teal-700 dark:text-teal-300">{{ $appt->scheduled_at->format('h:i A') }}</span>
                                <span class="text-[10px] text-teal-600 dark:text-teal-400 uppercase">{{ $appt->duration_minutes }}m</span>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('patients.show', $appt->patient) }}" class="font-bold text-slate-900 dark:text-slate-100 text-sm hover:text-teal-700 dark:hover:text-teal-400">
                                        {{ $appt->patient->name }}
                                    </a>
                                    <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500">({{ $appt->patient->patient_code }})</span>
                                    <span class="text-xs font-bold text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-950/60 px-2 py-0.5 rounded border border-sky-100 dark:border-sky-800/60">
                                        Session {{ $appt->session_number }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $appt->session_type }}</span> · 
                                    <span class="font-medium text-slate-600 dark:text-slate-300">{{ $appt->scheduled_at->format('l, d M Y') }}</span>
                                </div>
                                @if($appt->notes)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 italic">"{{ $appt->notes }}"</p>
                                @endif
                            </div>
                        </div>

                        <!-- Status & Controls -->
                        <div class="flex items-center gap-3 self-end sm:self-center">
                            <form method="POST" action="{{ route('appointments.update', $appt) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 py-1.5 px-3 focus:ring-teal-500 {{ $appt->status === 'completed' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300' : ($appt->status === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300' : 'bg-sky-50 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300') }}">
                                    <option value="scheduled" {{ $appt->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ $appt->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $appt->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="no_show" {{ $appt->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                            </form>

                            <a href="{{ route('patients.show', ['patient' => $appt->patient, 'tab' => 'progress']) }}" class="p-2 text-slate-400 hover:text-teal-700 dark:hover:text-teal-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Log Session SOAP Note">
                                <i data-lucide="clipboard-edit" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-slate-400 dark:text-slate-500">
                        <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600"></i>
                        <p class="text-sm font-semibold">No appointments scheduled.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side: Mini Calendar Info & Today's Schedule (1/3) -->
        <div class="space-y-6">
            <!-- Clinic Scheduling Details -->
            <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="info" class="w-4 h-4 text-teal-600 dark:text-teal-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Clinic Schedule Guidelines</h3>
                </div>

                <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-[#182a34]">
                        <span class="font-medium">Standard Therapy Slot:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">45-50 mins</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-[#182a34]">
                        <span class="font-medium">Clinical Intake Slot:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">60 mins</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-[#182a34]">
                        <span class="font-medium">Assessment / Psychometric:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">90 mins</span>
                    </div>
                </div>

                <button @click="newAppointmentModal = true" class="w-full py-2.5 bg-teal-50 dark:bg-teal-950/80 hover:bg-teal-100 dark:hover:bg-teal-900/60 text-teal-800 dark:text-teal-200 font-bold text-xs rounded-xl border border-teal-200 dark:border-teal-800/60 transition-colors cursor-pointer">
                    + Quick Schedule
                </button>
            </div>
        </div>

    </div>

    <!-- MODAL: SCHEDULE SESSION -->
    <div x-show="newAppointmentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 backdrop-blur-xs">
            <div @click.away="newAppointmentModal = false" class="bg-white dark:bg-[#122027] rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Schedule Clinical Session</h3>
                    <button @click="newAppointmentModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Select Patient <span class="text-rose-500">*</span></label>
                        <select name="patient_id" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->patient_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="scheduled_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Time <span class="text-rose-500">*</span></label>
                            <input type="time" name="scheduled_time" value="10:00" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Duration (minutes)</label>
                            <input type="number" name="duration_minutes" value="50" min="15" max="180" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Session # (optional)</label>
                            <input type="number" name="session_number" placeholder="Auto" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Session Modality / Type</label>
                        <select name="session_type" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500">
                            <option value="Psychotherapy Session">Psychotherapy Session</option>
                            <option value="Intake & Clinical Assessment">Intake & Clinical Assessment</option>
                            <option value="Cognitive Reframing Session">Cognitive Reframing Session</option>
                            <option value="Review & Maintenance Session">Review & Maintenance Session</option>
                            <option value="Parent Consultation">Parent Consultation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Consultation Agenda Notes</label>
                        <textarea name="notes" rows="2" placeholder="Planned interventions, discussion points..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2 px-3 focus:ring-teal-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="newAppointmentModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#0d5964] text-white text-xs font-semibold rounded-xl hover:bg-[#0a464f]">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
