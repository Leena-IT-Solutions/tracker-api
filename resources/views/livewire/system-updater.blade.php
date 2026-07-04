<?php

use App\Models\User;
use Illuminate\Support\Facades\Process;
use Livewire\Volt\Component;

new class extends Component
{
    public array $logs = [];
    public bool $updating = false;
    public string $currentCommit = '';
    public string $statusClass = '';
    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureGitSafeDirectory();
        $this->loadCurrentCommit();
    }

    public function ensureGitSafeDirectory(): void
    {
        Process::path(base_path())->run('git config --global --add safe.directory ' . base_path());
        Process::path(base_path())->run('git config --global --add safe.directory ' . dirname(base_path()));
    }

    public function loadCurrentCommit(): void
    {
        $this->ensureGitSafeDirectory();
        $result = Process::path(base_path())->run('git -c safe.directory=* log -1 --pretty=format:"%h - %s (%ci)"');
        if ($result->successful()) {
            $this->currentCommit = $result->output();
        } else {
            $this->currentCommit = 'Unknown (Git not initialized or no commits)';
        }
    }

    public function updateApp(): void
    {
        $this->updating = true;
        $this->logs = [];
        $this->statusMessage = 'Update in progress...';
        $this->statusClass = 'bg-blue-500/10 border-blue-500/25 text-blue-400';

        // 1. Git Pull
        $this->logMessage('Executing: git -c safe.directory=* pull');
        $gitResult = Process::path(base_path())->run('git -c safe.directory=* pull');
        $gitOutput = $gitResult->output() ?: $gitResult->errorOutput();
        $this->logMessage($gitOutput);
        
        if (!$gitResult->successful()) {
            $this->finishUpdate(false, 'Git Pull failed.');
            return;
        }

        // Detect file changes
        $composerChanged = str_contains($gitOutput, 'composer.json') || str_contains($gitOutput, 'composer.lock');
        $migrationsChanged = str_contains($gitOutput, 'database/migrations');
        $npmChanged = str_contains($gitOutput, 'package.json') || str_contains($gitOutput, 'package-lock.json');

        // 2. Composer Install
        if ($composerChanged) {
            $this->logMessage('Changes detected in composer configuration. Executing: composer install');
            $composerResult = Process::path(base_path())->run('composer install --no-interaction');
            $this->logMessage($composerResult->output() ?: $composerResult->errorOutput());
            if (!$composerResult->successful()) {
                $this->finishUpdate(false, 'Composer Install failed.');
                return;
            }
        } else {
            $this->logMessage('No changes in composer.json or composer.lock. Skipping composer install.');
        }

        // 3. Database Migrations
        if ($migrationsChanged) {
            $this->logMessage('New migrations detected. Executing: php artisan migrate --force');
        } else {
            $this->logMessage('Executing: php artisan migrate --force');
        }
        
        $migrationResult = Process::path(base_path())->run('php artisan migrate --force');
        $this->logMessage($migrationResult->output() ?: $migrationResult->errorOutput());
        if (!$migrationResult->successful()) {
            $this->finishUpdate(false, 'Database migrations failed.');
            return;
        }

        // 4. Cache Clear & Optimize
        $this->logMessage('Optimizing application caches. Executing: php artisan optimize:clear');
        $optimizeResult = Process::path(base_path())->run('php artisan optimize:clear');
        $this->logMessage($optimizeResult->output() ?: $optimizeResult->errorOutput());

        // 5. NPM Build if package changed
        if ($npmChanged) {
            $this->logMessage('Changes detected in npm dependencies. Executing: npm install && npm run build');
            $npmInstallResult = Process::path(base_path())->run('npm install');
            $this->logMessage($npmInstallResult->output() ?: $npmInstallResult->errorOutput());
            
            $npmBuildResult = Process::path(base_path())->run('npm run build');
            $this->logMessage($npmBuildResult->output() ?: $npmBuildResult->errorOutput());
        }

        $this->loadCurrentCommit();
        $this->finishUpdate(true, 'Application updated successfully!');
    }

    private function logMessage(string $message): void
    {
        $this->logs[] = [
            'time' => now()->format('H:i:s'),
            'text' => $message,
        ];
    }

    private function finishUpdate(bool $success, string $message): void
    {
        $this->updating = false;
        $this->statusMessage = $message;
        $this->statusClass = $success 
            ? 'bg-emerald-500/10 border-emerald-500/25 text-emerald-500' 
            : 'bg-rose-500/10 border-rose-500/25 text-rose-500';
    }
}; ?>

<div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-6 shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17" />
                </svg>
                <span>System Updates</span>
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Current commit: <span class="font-mono text-slate-700 dark:text-slate-350">{{ $currentCommit }}</span>
            </p>
        </div>

        <button wire:click="updateApp" wire:loading.attr="disabled" class="px-5 py-2.5 text-sm font-bold rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white shadow-lg shadow-indigo-500/10 flex items-center gap-2 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
            </svg>
            <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove>Update Application</span>
            <span wire:loading>Updating...</span>
        </button>
    </div>

    <!-- Status Alert Bar -->
    @if ($statusMessage)
        <div class="mb-6 p-4 rounded-2xl border text-sm font-semibold {{ $statusClass }}">
            {{ $statusMessage }}
        </div>
    @endif

    <!-- Terminal Log Console -->
    @if (!empty($logs))
        <div class="mt-4">
            <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Update Logs</h4>
            <div class="bg-slate-950 border border-slate-850 rounded-2xl p-4 font-mono text-xs text-slate-300 space-y-2 overflow-x-auto max-h-96">
                @foreach($logs as $log)
                    <div>
                        <span class="text-slate-500">[{{ $log['time'] }}]</span>
                        <span class="whitespace-pre-wrap">{{ $log['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
