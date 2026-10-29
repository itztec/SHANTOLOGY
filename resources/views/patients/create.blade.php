@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-700 dark:hover:text-teal-300 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Patient Directory</span>
        </a>
    </div>

    <!-- Intake Card -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-xs">
        <div class="pb-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-6 h-6 text-teal-600 dark:text-teal-400"></i>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">New Patient Intake</h1>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Register a new client and automatically provision their psychological case file.</p>
            </div>
            <div class="text-right">
                <span class="text-[11px] font-bold uppercase text-slate-400 dark:text-slate-500 block">Proposed ID</span>
                <span class="text-sm font-mono font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/80 px-2.5 py-1 rounded-md border border-teal-100 dark:border-teal-800/60">
                    {{ $proposedCode }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('patients.store') }}" class="mt-6 space-y-6">
            @csrf

            <!-- Section 1: Demographics -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-teal-100 dark:bg-teal-900/60 text-teal-800 dark:text-teal-200 text-[10px] flex items-center justify-center font-bold">1</span>
                    <span>Patient Demographics</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Riya Sharma" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Age <span class="text-rose-500">*</span></label>
                        <input type="number" name="age" value="{{ old('age') }}" min="1" max="120" required placeholder="e.g. 10" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Gender <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                            <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female (F)</option>
                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male (M)</option>
                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Contact Phone <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 9876543210" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. patient@example.com" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Residential Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, City, Area" 
                           class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>
            </div>

            <!-- Section 2: Guardian Details (For Minors) -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 text-[10px] flex items-center justify-center font-bold">2</span>
                    <span>Parent / Guardian Information (if applicable)</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Guardian Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="e.g. Alok Sharma" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Relationship</label>
                        <input type="text" name="guardian_relation" value="{{ old('guardian_relation') }}" placeholder="Father, Mother, Caregiver" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Guardian Phone</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="e.g. 9876543210" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Section 3: Clinical Status & Intake Notes -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-sky-100 dark:bg-sky-900/60 text-sky-800 dark:text-sky-200 text-[10px] flex items-center justify-center font-bold">3</span>
                    <span>Intake Configuration</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Date of Registration <span class="text-rose-500">*</span></label>
                        <input type="date" name="registration_date" value="{{ old('registration_date', date('Y-m-d')) }}" required 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Initial Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="follow_up" {{ old('status') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Referral Reason & Initial Clinical Notes</label>
                    <textarea name="notes" rows="3" placeholder="Brief notes on referral source, presenting symptoms, or intake observations..." 
                              class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('patients.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#0d5964] hover:bg-[#0a464f] text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                    Create & Open Clinical EMR
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
