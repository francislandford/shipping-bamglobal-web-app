<section class="w-full space-y-6">

    @include('partials.settings-heading')

    <flux:heading class="sr-only">
        {{ __('Roles settings') }}
    </flux:heading>

    <x-settings.layout
        :heading="__('Roles')"
        :subheading="__('Manage roles and assign grouped permissions to users through those roles.')"
    >

        <div class="space-y-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                    <span class="inline-block h-2 w-2 rounded-full bg-violet-500"></span>
                    {{ __('Access Control') }}
                </div>

                <div class="flex flex-wrap gap-2">
                    @can('create roles')
                        <flux:button :href="route('roles.create')" wire:navigate icon="plus">
                            {{ __('Add Role') }}
                        </flux:button>
                    @endcan

                    @can('export roles')
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" icon:trailing="chevron-down">
                                {{ __('Export') }}
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item :href="route('roles.export.csv', request()->query())" icon="arrow-down-tray">
                                    {{ __('Export CSV') }}
                                </flux:menu.item>
                                <flux:menu.item :href="route('roles.export.xlsx', request()->query())" icon="document-arrow-down">
                                    {{ __('Export Excel') }}
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    @endcan

                    @can('print roles')
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" icon:trailing="chevron-down">
                                {{ __('Print') }}
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item :href="route('roles.print', request()->query())" target="_blank" icon="printer">
                                    {{ __('Print View') }}
                                </flux:menu.item>
                                <flux:menu.item :href="route('roles.pdf', request()->query())" target="_blank" icon="document-text">
                                    {{ __('Download PDF') }}
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end">
                        <div class="flex-1">
                            <flux:input
                                wire:model.live.debounce.400ms="search"
                                :label="__('Search')"
                                :placeholder="__('Search role name')"
                            />
                        </div>

                        <div class="w-full md:w-48">
                            <flux:select wire:model.live="perPage" :label="__('Rows per page')">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </flux:select>
                        </div>

                        <div class="w-full md:w-48">
                            <flux:button variant="ghost" class="w-full" wire:click="resetFilters" icon="arrow-path">
                                {{ __('Reset Filters') }}
                            </flux:button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                        <tr class="border-b border-zinc-200 dark:border-zinc-800">
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('name')">{{ __('Role') }}</button>
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                {{ __('Permissions') }}
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('created_at')">{{ __('Created') }}</button>
                            </th>
                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($this->roles as $role)
                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $role->name }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $role->permissions_count }}
                                </td>

                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $role->created_at?->format('M d, Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @can('edit roles')
                                            <flux:button
                                                variant="ghost"
                                                size="sm"
                                                :href="route('roles.edit', $role)"
                                                wire:navigate
                                                icon="pencil-square"
                                            >
                                                {{ __('Edit') }}
                                            </flux:button>
                                        @endcan

                                        @can('delete roles')
                                            @if ($role->name !== 'Admin')
                                                <flux:button
                                                    variant="danger"
                                                    size="sm"
                                                    wire:click="delete({{ $role->id }})"
                                                    wire:confirm="{{ __('Are you sure you want to delete this role?') }}"
                                                    icon="trash"
                                                >
                                                    {{ __('Delete') }}
                                                </flux:button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-sm text-zinc-500">
                                    {{ __('No roles found.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
                    {{ $this->roles->links() }}
                </div>
            </div>

        </div>

    </x-settings.layout>

</section>
