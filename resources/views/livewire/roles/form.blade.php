<section class="w-full space-y-6">

    @include('partials.settings-heading')

    <flux:heading class="sr-only">
        {{ __('Role settings') }}
    </flux:heading>

    <x-settings.layout
        :heading="$role?->exists ? __('Edit Role') : __('Add Role')"
        :subheading="$role?->exists
            ? __('Update the role and its assigned permissions.')
            : __('Create a new role and assign permissions.')"
    >

        <div class="space-y-6">

            <div class="flex justify-end">
                <flux:button
                    variant="ghost"
                    :href="route('roles.index')"
                    wire:navigate
                    icon="arrow-left"
                >
                    {{ __('Back') }}
                </flux:button>
            </div>

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300">
                    {{ __('Please fix the errors and try again.') }}
                </div>
            @endif

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form wire:submit="save" class="space-y-6">

                    <div class="max-w-2xl">
                        <flux:input
                            wire:model="name"
                            :label="__('Role Name')"
                            :placeholder="__('Enter role name')"
                        />
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:heading size="sm">{{ __('Permissions') }}</flux:heading>
                            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('Select the permissions this role should have.') }}
                            </flux:text>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-3 rounded-2xl border border-zinc-200 px-4 py-3 dark:border-zinc-800">
                                    <input
                                        type="checkbox"
                                        value="{{ $permission->name }}"
                                        wire:model="selectedPermissions"
                                        class="rounded border-zinc-300 text-black focus:ring-black dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-white"
                                    >
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ $permission->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button
                            variant="ghost"
                            :href="route('roles.index')"
                            wire:navigate
                        >
                            {{ __('Cancel') }}
                        </flux:button>

                        <flux:button
                            variant="primary"
                            type="submit"
                            icon="check"
                        >
                            {{ $role?->exists ? __('Update Role') : __('Create Role') }}
                        </flux:button>
                    </div>

                </form>
            </div>

        </div>

    </x-settings.layout>

</section>
