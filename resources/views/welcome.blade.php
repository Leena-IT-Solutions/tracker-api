<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Tracker') }} - Real-time Tracking App</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-slate-950 text-slate-100 min-h-screen relative overflow-x-hidden selection:bg-violet-600 selection:text-white">
        <!-- Glowing background blobs -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute top-1/2 right-1/4 w-[450px] h-[450px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 min-h-screen flex flex-col justify-between">
            <!-- Navbar Header -->
            <header class="flex items-center justify-between py-6 border-b border-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/15">
                        <x-application-logo class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-violet-400 to-indigo-400">
                        Tracker
                    </span>
                </div>

                @if (Route::has('login'))
                    <livewire:welcome.navigation />
                @endif
            </header>

            <!-- Hero Section -->
            <main class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center py-12 sm:py-20">
                <!-- Left Content Column -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-xs font-semibold text-violet-400 tracking-wide uppercase">
                        🚀 Real-time Fleet Tracking
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                        Always know where <br/>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-violet-400 via-indigo-400 to-cyan-400">
                            they are, instantly.
                        </span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        The ultimate safety and real-time tracking application. Restrictive web dashboard control for Admins, combined with interactive, fluid experiences for Managers, Parents, Drivers, and Attendants on the mobile app.
                    </p>

                    <!-- Store Download Buttons -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                        <!-- Play Store Button -->
                        <a href="#" class="inline-flex items-center gap-3 bg-slate-900 border border-slate-800 hover:border-slate-700/80 rounded-2xl px-5 py-3 text-left transition-all duration-200 hover:-translate-y-0.5 group">
                            <svg class="w-8 h-8" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0z" fill="#00E5FF"/>
                                <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1z" fill="#FF3D00"/>
                                <path d="M385.4 337.8L104.6 499l220.7-126.6 60.1-60.1-60.1-6.5z" fill="#4CAF50"/>
                                <path d="M472.2 225.6l-58 33.2-60.1-60.1 60.1-60.1 58 33.2c19.1 11.1 27.6 28 27.6 46.9s-8.5 35.8-27.6 46.9z" fill="#FFC107"/>
                            </svg>
                            <div>
                                <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider leading-none">Get it on</div>
                                <div class="text-sm text-white font-bold tracking-tight">Google Play</div>
                            </div>
                        </a>

                        <!-- App Store Button -->
                        <a href="#" class="inline-flex items-center gap-3 bg-slate-900 border border-slate-800 hover:border-slate-700/80 rounded-2xl px-5 py-3 text-left transition-all duration-200 hover:-translate-y-0.5 group">
                            <svg class="w-8 h-8 text-white fill-current" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-48.7-22.7-77.9-22-37.1.5-74.8 21.6-93.8 55.7-40.2 71.6-10.2 178.6 27.9 234 18.6 26.6 40.5 56.2 69.3 55.1 27.5-1.1 38.1-17.7 73.8-17.7 35.5 0 45.1 17.7 73.8 17.1 29.2-.5 48.7-26.9 66.8-53.6 21-30.7 29.6-60.5 30.1-62.1-.8-.4-58.8-22.3-59.4-88.7zM299.8 81.7c15-18.2 24.9-43.8 22.1-69.7-22.6 1-49.9 15.2-66.1 34-14.3 16.6-26.7 42.6-23.3 68.2 25.1 2 51.1-13.8 67.3-32.5z"/>
                            </svg>
                            <div>
                                <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider leading-none">Download on the</div>
                                <div class="text-sm text-white font-bold tracking-tight">App Store</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right Graphic Column (Phone Mockup) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-[320px] aspect-[9/18.5] bg-slate-900 rounded-[50px] p-3 shadow-2xl ring-1 ring-slate-800/80 overflow-hidden">
                        <!-- Camera notch element -->
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 w-28 h-6 bg-black rounded-full z-20 flex items-center justify-between px-4">
                            <div class="w-3.5 h-3.5 rounded-full bg-slate-900"></div>
                            <div class="w-2 h-2 rounded-full bg-slate-900"></div>
                        </div>
                        
                        <!-- Screen wrapper -->
                        <div class="w-full h-full rounded-[40px] overflow-hidden bg-slate-950 relative z-10 border border-slate-800">
                            <img src="{{ asset('images/mobile_dashboard.png') }}" class="w-full h-full object-cover" alt="Mobile Dashboard Screenshot" />
                        </div>
                    </div>
                </div>
            </main>

            <!-- Roles Capabilities Grid -->
            <section class="py-12 border-t border-slate-900">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">One App, Multiple Roles</h2>
                    <p class="text-slate-400 mt-2 text-sm sm:text-base">Custom tailored features configured dynamically depending on user assignments.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Admin -->
                    <div class="bg-slate-900/40 border border-slate-900/60 rounded-3xl p-5 hover:border-violet-500/20 transition-all duration-200">
                        <div class="w-10 h-10 rounded-2xl bg-violet-600/10 border border-violet-500/20 flex items-center justify-center text-violet-400 font-bold mb-4">
                            AD
                        </div>
                        <h4 class="text-white font-bold text-base">Admin</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Full system configuration, global control, complete statistics, and organization setups via web app.</p>
                    </div>

                    <!-- Manager -->
                    <div class="bg-slate-900/40 border border-slate-900/60 rounded-3xl p-5 hover:border-indigo-500/20 transition-all duration-200">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold mb-4">
                            MG
                        </div>
                        <h4 class="text-white font-bold text-base">Manager</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Organization and group administrator. Coordinate runs, routes, drivers, and attendants.</p>
                    </div>

                    <!-- Parent -->
                    <div class="bg-slate-900/40 border border-slate-900/60 rounded-3xl p-5 hover:border-cyan-500/20 transition-all duration-200">
                        <div class="w-10 h-10 rounded-2xl bg-cyan-600/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 font-bold mb-4">
                            PT
                        </div>
                        <h4 class="text-white font-bold text-base">Parent</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Track student transit route, receive ETA alerts, boarding notifications, and check attendance logs.</p>
                    </div>

                    <!-- Driver -->
                    <div class="bg-slate-900/40 border border-slate-900/60 rounded-3xl p-5 hover:border-emerald-500/20 transition-all duration-200">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold mb-4">
                            DV
                        </div>
                        <h4 class="text-white font-bold text-base">Driver</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Get route directions, stop-by-stop schedule logs, and trigger alerts if delay happens.</p>
                    </div>

                    <!-- Attendant -->
                    <div class="bg-slate-900/40 border border-slate-900/60 rounded-3xl p-5 hover:border-amber-500/20 transition-all duration-200">
                        <div class="w-10 h-10 rounded-2xl bg-amber-600/10 border border-amber-500/20 flex items-center justify-center text-amber-400 font-bold mb-4">
                            AT
                        </div>
                        <h4 class="text-white font-bold text-base">Attendant</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Manage passenger boarding rosters, verify boarding passes, and update student status.</p>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="py-8 text-center text-xs text-slate-600 border-t border-slate-900/50 mt-12">
                Tracker &copy; {{ date('Y') }}. All rights reserved. Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </body>
</html>
