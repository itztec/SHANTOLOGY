<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MindCare - Psychology Clinic EMR' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Anti-FOUC Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('mindcare_theme') === 'dark' || (!('mindcare_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
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
<body class="h-full flex overflow-hidden text-slate-800 dark:text-slate-100 antialiased" 
      x-data="{ 
          mobileNavOpen: false, 
          searchOpen: false, 
          searchQuery: '', 
          searchResults: [], 
          isSearching: false,
          darkMode: document.documentElement.classList.contains('dark'),
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('mindcare_theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('mindcare_theme', 'light');
              }
              this.$nextTick(() => {
                  if (window.createIcons && window.lucideIcons) {
                      window.createIcons({ icons: window.lucideIcons });
                  }
              });
          }
      }">

    <!-- Mobile Navigation Backdrop -->
    <div x-show="mobileNavOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" 
         @click="mobileNavOpen = false"
         style="display: none;"></div>

    <!-- SIDEBAR -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#082f37] dark:bg-[#051c22] text-white flex flex-col justify-between transition-transform duration-300 transform lg:static lg:translate-x-0 border-r border-teal-900/40"
           :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <!-- Top Brand Section -->
        <div>
            <!-- Logo Header -->
            <div class="px-6 py-6 border-b border-teal-800/40">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <!-- Brand Brain Icon -->
                    <div class="w-10 h-10 rounded-xl bg-teal-500/20 border border-teal-400/30 flex items-center justify-center text-teal-300 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04"/>
                            <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xl font-bold tracking-tight text-white flex items-center gap-1">
                            MindCare
                        </div>
                        <div class="text-[11px] font-medium text-teal-300/80 tracking-wide">Better Minds · Brighter Futures</div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-6 space-y-1.5 font-medium text-sm">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard*') ? 'bg-[#0e7490] text-white font-semibold shadow-md shadow-cyan-900/30' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Patients -->
                <a href="{{ route('patients.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('patients.index') || request()->routeIs('patients.show') || request()->routeIs('patients.edit') ? 'bg-[#0e7490] text-white font-semibold shadow-md shadow-cyan-900/30' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Patients</span>
                </a>

                <!-- Add Patient -->
                <a href="{{ route('patients.create') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('patients.create') ? 'bg-[#0e7490] text-white font-semibold shadow-md shadow-cyan-900/30' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    <span>Add Patient</span>
                </a>

                <!-- Calendar -->
                <a href="{{ route('calendar.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('calendar*') || request()->routeIs('appointments*') ? 'bg-[#0e7490] text-white font-semibold shadow-md shadow-cyan-900/30' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span>Calendar</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('reports*') ? 'bg-[#0e7490] text-white font-semibold shadow-md shadow-cyan-900/30' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>Reports</span>
                </a>

                <!-- Backup Data -->
                <a href="{{ route('backup.download') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 hover:text-white hover:bg-white/5">
                    <i data-lucide="database-backup" class="w-5 h-5"></i>
                    <span>Download Backup</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Sidebar Section -->
        <div class="px-6 py-6 border-t border-teal-800/40">
            <!-- Leaf Illustration & Motto -->
            <div class="flex flex-col items-center justify-center p-4 rounded-2xl bg-teal-950/50 border border-teal-800/30 text-center mb-4">
                <div class="w-10 h-10 rounded-full bg-teal-500/20 flex items-center justify-center text-teal-300 mb-2">
                    <i data-lucide="sprout" class="w-5 h-5"></i>
                </div>
                <div class="text-xs font-semibold tracking-wide text-teal-200 uppercase">Heal · Understand</div>
                <div class="text-[11px] text-teal-400 font-medium">Support</div>
            </div>

            <!-- Clinician Mini Badge -->
            <div class="flex items-center justify-between text-xs text-slate-400 pt-2">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Clinic Portal Active</span>
                </span>
                <a href="{{ route('dashboard') }}" class="hover:text-white flex items-center gap-1 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f4f7f9] dark:bg-[#0b1318]">

        <!-- TOPBAR -->
        <header class="h-18 bg-white dark:bg-[#0f1b21] border-b border-slate-200/80 dark:border-slate-800 px-6 flex items-center justify-between shrink-0 shadow-xs z-30 transition-colors">
            <!-- Left: Mobile Toggle & Universal Patient Search -->
            <div class="flex items-center gap-4 flex-1 max-w-2xl">
                <!-- Mobile burger -->
                <button @click="mobileNavOpen = true" class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Search Input with Live Autocomplete -->
                <div class="relative w-full max-w-lg" @click.away="searchOpen = false">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text"
                               x-model="searchQuery"
                               @focus="if(searchQuery.length > 0) searchOpen = true"
                               @input.debounce.250ms="
                                    if(searchQuery.length >= 1) {
                                        isSearching = true;
                                        fetch('{{ route('quick-search') }}?q=' + encodeURIComponent(searchQuery))
                                            .then(res => res.json())
                                            .then(data => {
                                                searchResults = data;
                                                isSearching = false;
                                                searchOpen = true;
                                            });
                                    } else {
                                        searchResults = [];
                                        searchOpen = false;
                                    }
                               "
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-[#14232b] border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#182a34] focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-500 transition-all"
                               placeholder="Search patient by name, ID or phone number...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 bg-slate-200/60 dark:bg-slate-800 px-1.5 py-0.5 rounded">⌘K</span>
                        </div>
                    </div>

                    <!-- Autocomplete Dropdown Results -->
                    <div x-show="searchOpen && searchResults.length > 0" 
                         x-transition
                         class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#122027] rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50"
                         style="display: none;">
                        <div class="p-2 border-b border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-3">
                            Matched Patients (<span x-text="searchResults.length"></span>)
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-72 overflow-y-auto">
                            <template x-for="patient in searchResults" :key="patient.id">
                                <a :href="'/patients/' + patient.id" 
                                   class="flex items-center justify-between p-3 hover:bg-teal-50/60 dark:hover:bg-teal-950/40 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-teal-100 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 font-bold text-xs flex items-center justify-center">
                                            <span x-text="patient.name.substring(0, 2).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-800 dark:text-slate-100 group-hover:text-teal-600 dark:group-hover:text-teal-400 flex items-center gap-2">
                                                <span x-text="patient.name"></span>
                                                <span class="text-xs font-mono font-medium text-teal-600 bg-teal-50 dark:bg-teal-950/80 px-1.5 py-0.5 rounded border border-teal-100 dark:border-teal-800/60" x-text="patient.patient_code"></span>
                                            </div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500" x-text="patient.age + ' Yrs · ' + patient.gender + ' · ' + patient.phone"></div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-teal-600 dark:text-teal-400 flex items-center gap-1">
                                        <span>Open Record</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Theme Switcher, Notifications & User Profile -->
            <div class="flex items-center gap-3">
                
                <!-- Dark / Light Mode Toggle Button -->
                <button type="button" 
                        @click="toggleDarkMode()" 
                        class="p-2.5 rounded-xl text-slate-500 dark:text-slate-300 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer"
                        :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <!-- Sun Icon for Dark Mode -->
                    <span x-show="darkMode" style="display: none;">
                        <svg class="w-5 h-5 text-amber-400 fill-amber-400/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                        </svg>
                    </span>
                    <!-- Moon Icon for Light Mode -->
                    <span x-show="!darkMode">
                        <svg class="w-5 h-5 text-slate-600 fill-slate-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </span>
                </button>

                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-300 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-4 h-4 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white dark:ring-slate-900">3</span>
                    </button>
                    <!-- Notifications Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-[#122027] rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-4 z-50" style="display: none;">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">Notifications</h4>
                            <span class="text-xs text-teal-600 dark:text-teal-400 font-semibold cursor-pointer">Mark all read</span>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                            <div class="py-2.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">Session in 30 mins</p>
                                <p class="text-slate-500 dark:text-slate-400">Riya Sharma (P001) · 10:00 AM</p>
                            </div>
                            <div class="py-2.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">Assessment Pending</p>
                                <p class="text-slate-500 dark:text-slate-400">CDI-2 Screener ready for scoring</p>
                            </div>
                            <div class="py-2.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">Weekly Backup Completed</p>
                                <p class="text-slate-500 dark:text-slate-400">24 Patient clinical records verified</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinician Profile Card (From Screenshot 1) -->
                <div class="flex items-center gap-3 pl-2 border-l border-slate-200 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 ring-2 ring-teal-500/20 overflow-hidden flex items-center justify-center text-slate-600 dark:text-slate-200 font-semibold">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=150" 
                             alt="Dhritiman Dey" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <span class="hidden">DD</span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                            {{ $psychologist->name ?? 'Dhritiman Dey' }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            {{ $psychologist->title ?? 'Psychologist' }}
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                 class="mx-8 mt-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2 font-medium">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" 
                 class="mx-8 mt-4 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm shadow-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 dark:text-rose-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- PAGE CONTENT CONTAINER -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
