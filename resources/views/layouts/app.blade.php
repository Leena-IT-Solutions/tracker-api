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
    </head>
    <body class="font-sans antialiased text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
            
            <!-- Sidebar Navigation -->
            <livewire:layout.navigation />

            <!-- Main Content Container -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Top sticky header -->
                <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 h-16 flex items-center justify-between px-6 sticky top-0 z-20 transition-colors duration-300">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Hamburger Trigger -->
                        <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        @if (isset($header))
                            <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100 tracking-tight">
                                {{ $header }}
                            </h1>
                        @endif
                    </div>

                    <!-- User Actions/Profile Dropdown in Top Bar -->
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-indigo-500/10">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 md:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
