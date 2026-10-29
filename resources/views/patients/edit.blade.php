@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-700 dark:hover:text-teal-300 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to {{ $patient->name }}'s Record</span>
        </a>
    </div>

    <!-- Edit Demographics Card -->
    <div class="bg-white dark:bg-[#122027] rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-xs">
        <div class="pb-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-6 h-6 text-teal-600 dark:text-teal-400"></i>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Edit Demographics: {{ $patient->name }}</h1>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Update patient contact information and guardian details.</p>
            </div>
            <div class="text-right">
                <span class="text-sm font-mono font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/80 px-2.5 py-1 rounded-md border border-teal-100 dark:border-teal-800/60">
                    {{ $patient->patient_code }}
                </span>
            </div>
        </div>

        <form id="edit-patient-form" method="POST" action="{{ route('patients.update', $patient) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Demographics -->
            <div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}" required 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Age <span class="text-rose-500">*</span></label>
                        <input type="number" name="age" value="{{ old('age', $patient->age) }}" min="1" max="120" required 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Gender <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                            <option value="F" {{ old('gender', $patient->gender) === 'F' ? 'selected' : '' }}>Female (F)</option>
                            <option value="M" {{ old('gender', $patient->gender) === 'M' ? 'selected' : '' }}>Male (M)</option>
                            <option value="Other" {{ old('gender', $patient->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Contact Phone <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Residential Address</label>
                    <input type="text" name="address" value="{{ old('address', $patient->address) }}" 
                           class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>
            </div>

            <!-- Section 2: Guardian Details -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">Parent / Guardian Information</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Guardian Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $patient->guardian_name) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Relationship</label>
                        <input type="text" name="guardian_relation" value="{{ old('guardian_relation', $patient->guardian_relation) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Guardian Phone</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $patient->guardian_phone) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Section 3: Status -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Clinical Case Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                            <option value="in_progress" {{ old('status', $patient->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="follow_up" {{ old('status', $patient->status) === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            <option value="completed" {{ old('status', $patient->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="inactive" {{ old('status', $patient->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Clinical Notes</label>
                        <textarea name="notes" rows="2" class="w-full text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#182a34] text-slate-800 dark:text-slate-100 py-2.5 px-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">{{ old('notes', $patient->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" form="delete-patient-form" class="text-xs font-bold text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300">
                    Delete Patient
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('patients.show', $patient) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#0d5964] hover:bg-[#0a464f] text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-patient-form" method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Are you sure you want to delete this patient record?');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
