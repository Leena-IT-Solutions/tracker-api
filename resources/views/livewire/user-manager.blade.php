<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    // Search and filters
    public string $search = '';
    public string $roleFilter = '';
    
    // Form variables
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $mobile = '';
    public string $password = '';
    public array $selectedRoles = [];

    // Modal triggers
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    /**
     * Reset pagination when filters change.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset form fields.
     */
    public function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->mobile = '';
        $this->password = '';
        $this->selectedRoles = [];
        $this->resetErrorBag();
    }

    /**
     * Open create user modal.
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    /**
     * Create user.
     */
    public function createUser(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8'],
            'selectedRoles' => ['required', 'array', 'min:1'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['selectedRoles']);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('status', 'User created successfully.');
    }

    /**
     * Open edit user modal.
     */
    public function openEditModal(User $user): void
    {
        $this->resetForm();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->showEditModal = true;
    }

    /**
     * Update user.
     */
    public function updateUser(): void
    {
        $user = User::findOrFail($this->userId);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'mobile' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'selectedRoles' => ['required', 'array', 'min:1'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->roles()->sync($validated['selectedRoles']);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('status', 'User updated successfully.');
    }

    /**
     * Open delete modal.
     */
    public function openDeleteModal(User $user): void
    {
        $this->userId = $user->id;
        $this->showDeleteModal = true;
    }

    /**
     * Delete user.
     */
    public function deleteUser(): void
    {
        $user = User::findOrFail($this->userId);
        
        // Prevent deleting oneself
        if ($user->id === auth()->id()) {
            $this->showDeleteModal = false;
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->roles()->detach();
        $user->delete();

        $this->showDeleteModal = false;
        $this->userId = null;
        session()->flash('status', 'User deleted successfully.');
    }

    /**
     * Fetch users and roles.
     */
    public function with(): array
    {
        $query = User::with('roles')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('mobile', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->roleFilter)) {
            $query->whereHas('roles', function ($q) {
                $q->where('roles.id', $this->roleFilter);
            });
        }

        return [
            'users' => $query->paginate(6),
            'roles' => Role::all(),
        ];
    }
}; ?>

<div>
    <!-- Top Filter Bar -->
    <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-8">
        <!-- Search Input -->
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live="search" type="text" placeholder="Search by name, email, or mobile..." 
                   class="block w-full pl-10 pr-4 py-2.5 bg-slate-900/60 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent text-sm transition-colors duration-200" />
        </div>

        <!-- Filters & Action -->
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-stretch sm:items-center">
            <select wire:model.live="roleFilter" 
                    class="bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-colors duration-200">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                @endforeach
            </select>

            <button wire:click="openCreateModal" class="px-5 py-2.5 text-sm font-bold rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white shadow-lg shadow-indigo-500/10 flex items-center justify-center gap-2 transition duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add User</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-500 text-sm font-semibold">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-500 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Cards List (Full Width Cards) -->
    <div class="space-y-4">
        @forelse($users as $u)
            <div class="bg-white/85 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/40 rounded-3xl p-5 shadow-xl transition-all duration-350 hover:shadow-2xl hover:border-slate-300 dark:hover:border-slate-700/60">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Left Section: Avatar & Info -->
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-indigo-500/15 shrink-0">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        
                        <div class="min-w-0">
                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate">{{ $u->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $u->email }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 truncate mt-0.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $u->mobile }}
                            </p>
                        </div>
                    </div>

                    <!-- Middle Section: Roles Badges -->
                    <div class="flex flex-wrap gap-1.5 lg:justify-center lg:flex-1">
                        @foreach($u->roles as $role)
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-600 dark:text-violet-400 uppercase tracking-wider">
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Right Section: Action Buttons -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button wire:click="openEditModal({{ $u->id }})" class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition duration-150 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit</span>
                        </button>

                        <button wire:click="openDeleteModal({{ $u->id }})" class="px-4 py-2 text-xs font-semibold rounded-xl border border-rose-500/25 text-rose-500 hover:bg-rose-500/10 transition duration-150 flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed" {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-slate-400 dark:text-slate-500">
                No users found matching your criteria.
            </div>
        @endforelse
    </div>

    <!-- Pagination Container -->
    <div class="mt-8">
        {{ $users->links() }}
    </div>

    <!-- Create User Modal -->
    <div x-data="{ open: @entangle('showCreateModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 sm:p-8 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Create New User</h3>

                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <x-input-label for="new_name" :value="__('Name')" />
                        <x-text-input wire:model="name" id="new_name" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="new_email" :value="__('Email')" />
                        <x-text-input wire:model="email" id="new_email" type="email" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="new_mobile" :value="__('Mobile Number')" />
                        <x-text-input wire:model="mobile" id="new_mobile" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('mobile')" />
                    </div>

                    <div>
                        <x-input-label for="new_password" :value="__('Password')" />
                        <x-text-input wire:model="password" id="new_password" type="password" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <!-- Roles selectors -->
                    <div>
                        <span class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Assign Roles</span>
                        <div class="grid grid-cols-2 gap-3 p-4 bg-slate-950/40 border border-slate-850 rounded-2xl">
                            @foreach($roles as $role)
                                <label class="inline-flex items-center text-sm font-semibold text-slate-300 hover:text-white cursor-pointer select-none">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" 
                                           class="rounded border-slate-800 text-violet-600 focus:ring-violet-500 mr-2" />
                                    <span>{{ $role->display_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('selectedRoles')" />
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-850">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-semibold rounded-2xl border border-slate-800 hover:bg-slate-800 text-slate-300 transition duration-150">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white shadow-lg shadow-indigo-500/10 transition duration-150">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div x-data="{ open: @entangle('showEditModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 sm:p-8 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Edit User</h3>

                <form wire:submit.prevent="updateUser" class="space-y-4">
                    <div>
                        <x-input-label for="edit_name" :value="__('Name')" />
                        <x-text-input wire:model="name" id="edit_name" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="edit_email" :value="__('Email')" />
                        <x-text-input wire:model="email" id="edit_email" type="email" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="edit_mobile" :value="__('Mobile Number')" />
                        <x-text-input wire:model="mobile" id="edit_mobile" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('mobile')" />
                    </div>

                    <div>
                        <x-input-label for="edit_password" :value="__('Password (Leave blank to keep current)')" />
                        <x-text-input wire:model="password" id="edit_password" type="password" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <!-- Roles selectors -->
                    <div>
                        <span class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Assign Roles</span>
                        <div class="grid grid-cols-2 gap-3 p-4 bg-slate-950/40 border border-slate-850 rounded-2xl">
                            @foreach($roles as $role)
                                <label class="inline-flex items-center text-sm font-semibold text-slate-300 hover:text-white cursor-pointer select-none">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" 
                                           class="rounded border-slate-800 text-violet-600 focus:ring-violet-500 mr-2" />
                                    <span>{{ $role->display_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('selectedRoles')" />
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-850">
                        <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-semibold rounded-2xl border border-slate-800 hover:bg-slate-800 text-slate-300 transition duration-150">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white shadow-lg shadow-indigo-500/10 transition duration-150">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div x-data="{ open: @entangle('showDeleteModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-4">Confirm Deletion</h3>
                <p class="text-sm text-slate-400 mb-6">Are you sure you want to delete this user? This action will permanently remove their account and all associated roles from the system.</p>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-850">
                    <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-semibold rounded-2xl border border-slate-800 hover:bg-slate-800 text-slate-300 transition duration-150">
                        Cancel
                    </button>
                    <button wire:click="deleteUser" class="px-5 py-2.5 text-sm font-bold rounded-2xl bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-500/10 transition duration-150">
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
