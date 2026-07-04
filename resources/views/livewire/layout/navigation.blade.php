<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Desktop Sidebar (Hidden on mobile) -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 hidden md:flex flex-col h-screen sticky top-0 z-30 transition-all duration-300">
        <!-- Brand Header -->
        <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800">
            <div class="p-2 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-xl shadow-md shadow-indigo-500/10">
                <x-application-logo class="w-5 h-5 text-white" />
            </div>
            <span class="text-lg font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-violet-400 to-indigo-400">
                Tracker
            </span>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('users.index') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>User Manager</span>
            </a>

            <a href="{{ route('profile') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
            </a>
        </nav>

        <!-- Footer / Logout -->
        <div class="p-4 border-t border-slate-800">
            <button wire:click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-2xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition duration-150 text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log Out</span>
            </button>
        </div>
    </aside>

    <!-- Mobile Sidebar Drawer (Slides out from left) -->
    <div x-show="sidebarOpen" class="relative z-50 md:hidden" x-ref="dialog" role="dialog" aria-modal="true" style="display: none;">
        <!-- Backdrop Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"
             @click="sidebarOpen = false"></div>

        <div class="fixed inset-0 flex">
            <!-- Sidebar Panel -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-900 text-slate-300 pt-5 pb-4"
                 @click.away="sidebarOpen = false">
                
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Brand Header -->
                <div class="flex items-center gap-3 px-6 pb-4 border-b border-slate-800">
                    <div class="p-2 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-xl">
                        <x-application-logo class="w-5 h-5 text-white" />
                    </div>
                    <span class="text-lg font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-violet-400 to-indigo-400">
                        Tracker
                    </span>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" wire:navigate 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('users.index') }}" wire:navigate 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>User Manager</span>
                    </a>

                    <a href="{{ route('profile') }}" wire:navigate 
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-violet-600/10 text-violet-400 border-l-4 border-violet-500' : 'hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </nav>

                <!-- Footer / Logout -->
                <div class="p-4 border-t border-slate-800">
                    <button wire:click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-2xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition duration-150 text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log Out</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
