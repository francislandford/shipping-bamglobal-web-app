<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $user?->exists ? 'Edit User' : 'Add User' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                {{ $user?->exists ? 'Update user account details and assigned roles.' : 'Create a new user account and assign roles.' }}
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('users.index')" wire:navigate icon="arrow-left">
            Back
        </flux:button>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300">
            Please fix the errors and try again.
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <flux:input
                    wire:model="name"
                    label="Full Name"
                    placeholder="Enter full name"
                />

                <flux:input
                    wire:model="email"
                    type="email"
                    label="Email Address"
                    placeholder="email@example.com"
                />

                <flux:input
                    wire:model="password"
                    type="password"
                    label="{{ $user?->exists ? 'New Password (Optional)' : 'Password' }}"
                    placeholder="Enter password"
                    viewable
                />

                <flux:input
                    wire:model="password_confirmation"
                    type="password"
                    label="Confirm Password"
                    placeholder="Confirm password"
                    viewable
                />
            </div>

            <div class="space-y-3">
                <flux:heading size="sm">Roles</flux:heading>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-3 rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-800">
                            <input
                                type="checkbox"
                                value="{{ $role->name }}"
                                wire:model="selectedRoles"
                                class="rounded border-zinc-300 text-black focus:ring-black dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-white"
                            >
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('selectedRoles.*')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <flux:checkbox wire:model="is_active" label="Active user" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('users.index')" wire:navigate>
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="submit" icon="check">
                    {{ $user?->exists ? 'Update User' : 'Create User' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
