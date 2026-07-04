<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tracker') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes pulse-slow {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-30px) scale(1.08); }
            }
            @keyframes pulse-slow-reverse {
                0%, 100% { transform: translateY(0) scale(1.05); }
                50% { transform: translateY(30px) scale(0.95); }
            }
            .animate-pulse-slow {
                animation: pulse-slow 10s ease-in-out infinite;
            }
            .animate-pulse-slow-reverse {
                animation: pulse-slow-reverse 14s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 dark:text-slate-100 antialiased selection:bg-violet-500 selection:text-white">
        <div class="min-h-screen relative flex flex-col justify-center items-center p-4 sm:p-6 overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full bg-violet-600/20 dark:bg-violet-600/10 blur-3xl animate-pulse-slow"></div>
                <div class="absolute top-1/3 -right-40 w-[600px] h-[600px] rounded-full bg-fuchsia-600/15 dark:bg-fuchsia-600/10 blur-3xl animate-pulse-slow-reverse"></div>
                <div class="absolute -bottom-40 left-1/4 w-[400px] h-[400px] rounded-full bg-indigo-600/20 dark:bg-indigo-600/10 blur-3xl animate-pulse-slow"></div>
            </div>

            <!-- Content Wrapper -->
            <div class="relative z-10 w-full flex flex-col items-center">
                <!-- Logo -->
                <div class="mb-8 transform hover:scale-105 transition-all duration-300">
                    <a href="/" wire:navigate class="flex items-center gap-3 group">
                        <div class="p-3 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-2xl shadow-xl shadow-indigo-500/20 group-hover:shadow-indigo-500/35 transition-all duration-300">
                            <x-application-logo class="w-8 h-8 text-white" />
                        </div>
                        <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-violet-600 to-indigo-600 dark:from-violet-400 dark:to-indigo-400">
                            Tracker
                        </span>
                    </a>
                </div>

                <!-- Glassmorphic Card -->
                <div class="w-full sm:max-w-md px-8 py-10 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 shadow-2xl rounded-3xl transition-all duration-300">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
