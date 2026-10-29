<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SHANTOLOGY - Clinician Portal Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Anti-FOUC Dark Mode Initializer -->
    <script>
        const savedTheme = localStorage.getItem('shantology_theme') || localStorage.getItem('mindcare_theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="h-full min-h-screen bg-[#f4f7f9] dark:bg-[#0b1318] text-slate-800 dark:text-slate-100 flex flex-col justify-between antialiased transition-colors"
      x-data="{ 
          loginId: 'admin', 
          password: '', 
          showPassword: false,
          darkMode: document.documentElement.classList.contains('dark'),
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('shantology_theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('shantology_theme', 'light');
              }
              this.$nextTick(() => {
                  if (window.createIcons && window.lucideIcons) {
                      window.createIcons({ icons: window.lucideIcons });
                  }
              });
          },
          quickFill() {
              this.loginId = 'admin';
              this.password = '1234';
          }
      }">

    <!-- Top Floating Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#082f37] dark:bg-teal-500/20 border border-teal-800 dark:border-teal-400/30 flex items-center justify-center text-teal-300 shadow-sm">
                <svg class="w-6 h-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04"/>
                    <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04"/>
                </svg>
            </div>
            <div>
                <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">SHANTOLOGY</span>
                <span class="text-[11px] block font-semibold text-teal-700 dark:text-teal-400 uppercase tracking-wider">Psychology Clinic EMR</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Dark / Light Mode Switcher -->
            <button type="button" 
                    @click="toggleDarkMode()" 
                    class="p-2.5 rounded-xl bg-white dark:bg-[#122027] text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800 shadow-2xs hover:shadow-sm transition-all cursor-pointer">
                <!-- Sun Icon for Dark Mode -->
                <span x-show="darkMode" style="display: none;">
                    <svg class="w-4 h-4 text-amber-400 fill-amber-400/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                </span>
                <!-- Moon Icon for Light Mode -->
                <span x-show="!darkMode">
                    <svg class="w-4 h-4 text-slate-600 fill-slate-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </span>
            </button>

            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 dark:bg-teal-950/60 border border-teal-200/80 dark:border-teal-800/60 text-xs font-semibold text-teal-800 dark:text-teal-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>EMR System Online</span>
            </span>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Clinical Showcase (7 cols on lg) -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-100/70 dark:bg-teal-900/40 text-teal-800 dark:text-teal-300 text-xs font-bold tracking-wide">
                    <i data-lucide="shield-check" class="w-4 h-4 text-teal-600 dark:text-teal-400"></i>
                    <span>Secure Clinical Psychology Management</span>
                </div>

                <div class="space-y-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        Empowering Clinicians. <br class="hidden sm:inline"/>
                        <span class="text-[#0d5964] dark:text-teal-400">Healing Minds.</span>
                    </h1>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        SHANTOLOGY delivers comprehensive psychiatric case formulation, standardized MSE recording, psychometric evaluation, and goal-oriented psychotherapy session tracking in one unified EMR.
                    </p>
                </div>

                <!-- Feature Badges Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 text-left">
                    <div class="p-3.5 rounded-2xl bg-white dark:bg-[#122027] border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 flex items-center justify-center mb-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Case History</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">HOPI & Family Background</p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white dark:bg-[#122027] border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center mb-2">
                            <i data-lucide="brain" class="w-4 h-4"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">MSE & Assessment</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">GAD-7, CDI-2 & Vanderbilt</p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white dark:bg-[#122027] border border-slate-200/80 dark:border-slate-800 shadow-2xs col-span-2 sm:col-span-1">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 flex items-center justify-center mb-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">SOAP Notes & Cal</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Session Logs & Goals</p>
                    </div>
                </div>

                <!-- Clinical Quote Pill -->
                <div class="p-4 rounded-2xl bg-teal-900/5 dark:bg-teal-950/40 border border-teal-800/10 dark:border-teal-800/30 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-teal-600/20 text-teal-700 dark:text-teal-300 flex items-center justify-center shrink-0">
                        <i data-lucide="quote" class="w-4 h-4"></i>
                    </div>
                    <p class="text-xs italic text-slate-600 dark:text-slate-300">
                        "Dedicated to evidence-based psychotherapy, structured evaluations, and compassionate behavioral healthcare."
                    </p>
                </div>
            </div>

            <!-- Right Login Form Card (5 cols on lg) -->
            <div class="lg:col-span-5 w-full">
                <div class="bg-white dark:bg-[#122027] rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
                    
                    <!-- Top Accent Bar -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#0d5964] via-teal-500 to-emerald-400"></div>

                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Clinician Portal</h2>
                            <span class="text-xs font-bold font-mono text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/80 px-2 py-0.5 rounded-md border border-teal-200 dark:border-teal-800/60">EMR v1.0</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Please authenticate with your clinician credentials to access patient files.</p>
                    </div>

                    <!-- Quick Demo Credential Banner -->
                    <div class="mb-5 p-3 rounded-2xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                        <div class="text-xs">
                            <div class="font-bold text-amber-900 dark:text-amber-200 flex items-center gap-1.5">
                                <i data-lucide="key-round" class="w-3.5 h-3.5 text-amber-600"></i>
                                <span>Default Login Access</span>
                            </div>
                            <div class="text-[11px] text-amber-700 dark:text-amber-300 mt-0.5 font-mono">
                                ID: <span class="font-bold">admin</span> · Pass: <span class="font-bold">1234</span>
                            </div>
                        </div>

                        <button type="button" 
                                @click="quickFill()" 
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-2xs transition-colors cursor-pointer self-start sm:self-auto">
                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                            <span>1-Click Fill</span>
                        </button>
                    </div>

                    <!-- Status or Error alerts -->
                    @if(session('status'))
                        <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 text-xs font-semibold text-emerald-800 dark:text-emerald-200 flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/60 text-xs font-semibold text-rose-800 dark:text-rose-200 flex items-start gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                        @csrf

                        <!-- Clinician ID -->
                        <div>
                            <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Clinician ID / Username <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <input type="text" 
                                       id="login" 
                                       name="login" 
                                       x-model="loginId"
                                       required 
                                       autofocus
                                       placeholder="Enter ID (e.g. admin)" 
                                       class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#182a34] border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-[#1e3440] focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-medium">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Password <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-teal-600 dark:text-teal-400 font-medium font-mono">PIN: 1234</span>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </div>
                                <input :type="showPassword ? 'text' : 'password'" 
                                       id="password" 
                                       name="password" 
                                       x-model="password"
                                       required 
                                       placeholder="••••••••" 
                                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-[#182a34] border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-[#1e3440] focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-medium">
                                
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                    <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-[#182a34]">
                                <span class="text-xs text-slate-600 dark:text-slate-400">Remember on this workstation</span>
                            </label>
                        </div>

                        <!-- Sign In Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-[#0d5964] hover:bg-[#0a464f] text-white font-bold text-sm rounded-xl shadow-md shadow-teal-900/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer group">
                                <span>Sign In to Clinical EMR</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Bottom Security Notice -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400"></i>
                            <span>Confidential Psychology Healthcare Portal</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-xs text-slate-400 dark:text-slate-500">
        <div>SHANTOLOGY Psychology Clinic & EMR © {{ date('Y') }} · Better Minds · Brighter Futures</div>
    </footer>

</body>
</html>
