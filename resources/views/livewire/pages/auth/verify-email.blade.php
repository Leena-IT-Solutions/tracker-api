<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

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
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Verify Email</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Please confirm your email address.</p>
    </div>

    <div class="mb-6 text-sm text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/40 p-4 rounded-2xl border border-slate-200/50 dark:border-slate-800/40 leading-relaxed">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-sm font-medium text-emerald-600 dark:text-emerald-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-4 items-center justify-between sm:flex-row">
        <x-primary-button wire:click="sendVerification" class="w-full sm:w-auto">
            {{ __('Resend Verification Email') }}
        </x-primary-button>

        <button wire:click="logout" type="submit" class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition duration-150">
            {{ __('Log Out') }}
        </button>
    </div>
</div>
