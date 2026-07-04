<?php

use App\Models\User;
use App\Models\Role;
use Livewire\Volt\Component;

new class extends Component
{
    public function with(): array
    {
        return [
            'totalUsers' => User::count(),
            'totalAdmins' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'totalManagers' => User::whereHas('roles', fn($q) => $q->where('name', 'manager'))->count(),
            'totalParents' => User::whereHas('roles', fn($q) => $q->where('name', 'parent'))->count(),
            'totalDrivers' => User::whereHas('roles', fn($q) => $q->where('name', 'driver'))->count(),
            'totalAttendants' => User::whereHas('roles', fn($q) => $q->where('name', 'attendant'))->count(),
            'totalChildren' => 0, // Placeholder/Mocked for now
        ];
    }
}; ?>

<div class="space-y-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1: Total Users -->
        <div class="sm:col-span-2 lg:col-span-3 bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-violet-500/10 border border-violet-500/20 text-violet-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalUsers }}</h3>
            </div>
        </div>

        <!-- Card 2: Admins -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Admins</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalAdmins }}</h3>
            </div>
        </div>

        <!-- Card 3: Managers -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-blue-500/10 border border-blue-500/20 text-blue-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Managers</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalManagers }}</h3>
            </div>
        </div>

        <!-- Card 4: Parents -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-indigo-500/10 border border-indigo-500/20 text-indigo-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Parents</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalParents }}</h3>
            </div>
        </div>

        <!-- Card 5: Drivers -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 011-1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Drivers</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalDrivers }}</h3>
            </div>
        </div>

        <!-- Card 6: Attendants -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Attendants</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalAttendants }}</h3>
            </div>
        </div>

        <!-- Card 7: Childrens -->
        <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl flex items-center gap-5">
            <div class="p-4 bg-teal-500/10 border border-teal-500/20 text-teal-500 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Childrens</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $totalChildren }}</h3>
            </div>
        </div>
    </div>
</div>
