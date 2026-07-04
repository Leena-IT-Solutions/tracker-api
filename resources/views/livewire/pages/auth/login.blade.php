<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-rose-600 dark:text-rose-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Welcome Back</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Please sign in to access your dashboard.</p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <!-- Email or Mobile -->
        <div>
            <x-input-label for="email" :value="__('Email or Mobile')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="text" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 dark:border-slate-800 text-violet-600 shadow-sm focus:ring-violet-500/25 dark:bg-slate-950 dark:focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:text-violet-500 dark:hover:text-violet-300 transition duration-150" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="w-full">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" wire:navigate class="font-medium text-violet-600 dark:text-violet-400 hover:underline">
                    Sign Up
                </a>
            </p>
        </div>
    </form>
</div>
