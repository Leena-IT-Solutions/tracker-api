<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Forgot Password</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Recover your account credentials.</p>
    </div>

    <div class="mb-6 text-sm text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/40 p-4 rounded-2xl border border-slate-200/50 dark:border-slate-800/40 leading-relaxed">
        {{ __('Enter your email address below, and we will email you a password reset link to set up a new password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">
                Back to Sign In
            </a>
        </div>
    </form>
</div>
