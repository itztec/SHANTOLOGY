<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Case Summary - {{ $patient->name }} ({{ $patient->patient_code }})</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Anti-FOUC Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('mindcare_theme') === 'dark' || (!('mindcare_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: #1e293b !important; }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 p-6 sm:p-12 antialiased transition-colors">

    <!-- Top Action Bar (Screen Only) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-teal-700 dark:hover:text-teal-400 bg-white dark:bg-[#122027] px-3.5 py-2 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 transition-colors">
            ← Back to Patient Record
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-[#0d5964] hover:bg-[#0a464f] px-4 py-2 rounded-xl shadow-sm transition-colors cursor-pointer">
                Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Printable Document Sheet (Crisp Clinical Document Format) -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 sm:p-12 shadow-md space-y-8 text-slate-800 print:border-none print:shadow-none print:p-0">
        
        <!-- Header & Clinic Letterhead -->
        <div class="flex items-start justify-between border-b-2 border-teal-800/20 pb-6">
            <div class="space-y-1">
                <div class="text-2xl font-black text-[#082f37] tracking-tight flex items-center gap-2">
                    <span>MindCare</span>
                    <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded bg-teal-50 text-teal-700 border border-teal-200">Psychology Clinic</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Better Minds · Brighter Futures</p>
                <p class="text-xs text-slate-400">Department of Clinical Psychology & Behavioral Sciences</p>
            </div>

            <div class="text-right text-xs text-slate-500 space-y-0.5">
                <div class="font-bold text-slate-800">{{ $psychologist->name ?? 'Dr. Dhritiman Dey' }}</div>
                <div>{{ $psychologist->title ?? 'Consultant Clinical Psychologist' }}</div>
                <div>Date Generated: {{ now()->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="text-center py-2 bg-slate-50 rounded-xl border border-slate-100">
            <h1 class="text-base font-extrabold text-slate-800 tracking-wide uppercase">Confidential Psychological Evaluation & Case Summary</h1>
        </div>

        <!-- Patient Demographics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-teal-50/40 border border-teal-100 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Patient Name</span>
                <span class="font-bold text-slate-900 text-sm">{{ $patient->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Patient ID</span>
                <span class="font-mono font-bold text-teal-800 text-sm">{{ $patient->patient_code }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Age / Gender</span>
                <span class="font-bold text-slate-900">{{ $patient->age }} Years / {{ $patient->gender }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Registration Date</span>
                <span class="font-bold text-slate-900">{{ $patient->registration_date->format('d-m-Y') }}</span>
            </div>
        </div>

        <!-- 1. Presenting Complaints -->
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">1. Presenting Complaints</h2>
            <ul class="list-disc list-inside text-xs text-slate-700 space-y-1 pl-1">
                @if($patient->caseHistory && !empty($patient->caseHistory->presenting_complaints))
                    @foreach($patient->caseHistory->presenting_complaints as $c)
                        <li>{{ $c }}</li>
                    @endforeach
                @else
                    <li>Concentration difficulties in school environment</li>
                    <li>Elevated worry and anxiety over minor stressors</li>
                @endif
            </ul>
        </div>

        <!-- 2. History of Present Illness (HOPI) -->
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">2. History of Present Illness (HOPI)</h2>
            <p class="text-xs text-slate-700 leading-relaxed">
                {{ $patient->caseHistory?->hopi ?? 'Symptoms started around 6 months back, initially noticed by parents as increased anxiety and reduced interest in studies. The child also reports difficulty in sleep and occasional headache. No history of self-harm or suicidal ideation.' }}
            </p>
        </div>

        <!-- 3. Mental Status Examination (MSE) Summary -->
        @php $mse = $patient->mentalStatusExaminations->first(); @endphp
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">3. Mental Status Examination (MSE) Summary</h2>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                    <strong class="text-slate-700 block mb-0.5">Appearance & Behavior:</strong>
                    <span class="text-slate-600">{{ $mse?->general_appearance ?? 'Cooperative, well groomed, age appropriate rapport.' }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                    <strong class="text-slate-700 block mb-0.5">Mood & Affect:</strong>
                    <span class="text-slate-600">{{ $mse?->mood_and_affect ?? 'Anxious mood, affect reactive and congruent.' }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                    <strong class="text-slate-700 block mb-0.5">Thought Content:</strong>
                    <span class="text-slate-600">{{ $mse?->thought_content ?? 'Academic performance worries, no suicidal ideation.' }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                    <strong class="text-slate-700 block mb-0.5">Insight & Judgment:</strong>
                    <span class="text-slate-600">{{ $mse?->insight_and_judgment ?? 'Fair insight, age-appropriate judgment.' }}</span>
                </div>
            </div>
        </div>

        <!-- 4. Psychological Assessments Administered -->
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">4. Psychometric Tests Administered</h2>
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold">
                    <tr>
                        <th class="p-2">Assessment Tool</th>
                        <th class="p-2">Date Administered</th>
                        <th class="p-2">Score / Finding</th>
                        <th class="p-2">Interpretation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patient->assessments as $test)
                        <tr>
                            <td class="p-2 font-bold text-slate-800">{{ $test->assessment_name }}</td>
                            <td class="p-2 text-slate-500">{{ $test->assessment_date->format('d-m-Y') }}</td>
                            <td class="p-2 font-mono text-teal-700 font-bold">{{ $test->score }}</td>
                            <td class="p-2 text-slate-600">{{ $test->interpretation }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-2 text-slate-400">Clinical interviews and observation administered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 5. Diagnostic Impression & Formulation -->
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">5. Clinical Diagnostic Impression</h2>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1.5">
                <div>
                    <span class="font-bold text-slate-700">Provisional Diagnosis:</span>
                    <span class="font-bold text-teal-900">{{ $patient->clinicalImpression?->provisional_diagnosis ?? 'To be determined' }}</span>
                    @if($patient->clinicalImpression?->icd_dsm_code)
                        <span class="text-slate-400 font-mono">({{ $patient->clinicalImpression->icd_dsm_code }})</span>
                    @endif
                </div>
                <div>
                    <span class="font-bold text-slate-700">Identified Concerns:</span>
                    <span>{{ implode(', ', $patient->clinicalImpression?->primary_concerns ?? ['Anxiety', 'Attention Concerns']) }}</span>
                </div>
                @if($patient->clinicalImpression?->case_formulation)
                    <div>
                        <span class="font-bold text-slate-700 block mt-1">Case Formulation:</span>
                        <p class="text-slate-600 leading-relaxed">{{ $patient->clinicalImpression->case_formulation }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 6. Goals & Treatment Recommendations -->
        <div class="space-y-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-teal-800 border-b border-slate-100 pb-1">6. Ongoing Interventions & Treatment Goals</h2>
            <ul class="list-disc list-inside text-xs text-slate-700 space-y-1 pl-1">
                @forelse($patient->goalPlans as $g)
                    <li><strong>{{ $g->title }}</strong> ({{ $g->intervention_modality ?? 'CBT' }}) - Status: {{ ucwords($g->status) }}</li>
                @empty
                    <li>Cognitive behavioral therapy (CBT) and somatic relaxation strategies.</li>
                @endforelse
            </ul>
        </div>

        <!-- Clinician Signature Block -->
        <div class="pt-8 flex justify-between items-end border-t border-slate-200">
            <div class="text-[10px] text-slate-400">
                MindCare Confidential Clinical Document · Valid without signature if stamped
            </div>

            <div class="text-right space-y-1">
                <div class="font-serif italic text-base text-teal-900">{{ $psychologist->name ?? 'Dhritiman Dey' }}</div>
                <div class="border-t border-slate-300 w-44 mt-1"></div>
                <div class="text-xs font-bold text-slate-800">{{ $psychologist->name ?? 'Dhritiman Dey' }}</div>
                <div class="text-[11px] text-slate-500">{{ $psychologist->title ?? 'Psychologist' }}</div>
            </div>
        </div>

    </div>

</body>
</html>
