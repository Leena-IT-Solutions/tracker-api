<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Summary Card (Full Width) -->
            <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 sm:p-8 shadow-xl transition-colors duration-300">
                <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                    <!-- Initials Avatar Widget -->
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-3xl shadow-lg shadow-indigo-500/20 shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    
                    <div class="flex-1 space-y-2">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 leading-none">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->mobile)
                            <p class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5 justify-center sm:justify-start">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ auth()->user()->mobile }}
                            </p>
                        @endif

                        <!-- Roles Section -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800/60 mt-4">
                            <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Roles</div>
                            <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                                @forelse(auth()->user()->roles as $role)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-600 dark:text-violet-400">
                                        {{ $role->display_name }}
                                    </span>
                                @empty
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-500/10 border border-slate-500/20 text-slate-500">
                                        No Roles
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Forms Stack -->
            <div class="space-y-8">
                <!-- Profile Information -->
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 sm:p-8 shadow-xl transition-colors duration-300">
                    <livewire:profile.update-profile-information-form />
                </div>

                <!-- Change Password -->
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 sm:p-8 shadow-xl transition-colors duration-300">
                    <livewire:profile.update-password-form />
                </div>

                <!-- Delete User Account (Danger Zone) -->
                <div class="bg-rose-500/5 border border-rose-500/20 rounded-3xl p-6 sm:p-8 shadow-xl transition-colors duration-300">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
