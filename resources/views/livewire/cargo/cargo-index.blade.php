<section class="w-full space-y-6">

    <flux:heading class="sr-only">{{ __('Cargo settings') }}</flux:heading>
        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                    Master Data
                </div>

                <div class="flex flex-wrap gap-2">
                    @can('create cargos')
                        <flux:button :href="route('cargos.create')" wire:navigate icon="plus">
                            Add Cargo
                        </flux:button>
                    @endcan

                    @can('export cargos')
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" icon:trailing="chevron-down">
                                Export
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item :href="route('cargos.export.csv', request()->query())" icon="arrow-down-tray">
                                    Export CSV
                                </flux:menu.item>
                                <flux:menu.item :href="route('cargos.export.xlsx', request()->query())" icon="document-arrow-down">
                                    Export Excel
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    @endcan

                    @can('print cargos')
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" icon:trailing="chevron-down">
                                Print
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item :href="route('cargos.print', request()->query())" target="_blank" icon="printer">
                                    Print View
                                </flux:menu.item>
                                <flux:menu.item :href="route('cargos.pdf', request()->query())" target="_blank" icon="document-text">
                                    Download PDF
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
                                :placeholder="__('Search cargo name, code, type or UOM')"
                            />
                        </div>

                        <div class="w-full md:w-48">
                            <flux:select wire:model.live="status" :label="__('Status')">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </flux:select>
                        </div>

                        <div class="w-full md:w-40">
                            <flux:select wire:model.live="perPage" :label="__('Rows')">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </flux:select>
                        </div>

                        <div class="w-full md:w-48">
                            <flux:button variant="ghost" class="w-full" wire:click="resetFilters" icon="arrow-path">
                                Reset Filters
                            </flux:button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                        <tr class="border-b border-zinc-200 dark:border-zinc-800">
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('name')">Cargo Name</button>
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('code')">Code</button>
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('type')">Type</button>
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('uom')">UOM</button>
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                <button wire:click="sortBy('is_active')">Status</button>
                            </th>
                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Actions
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($this->cargos as $cargo)
                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $cargo->name }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $cargo->description ?: 'No description' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $cargo->code ?: '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $cargo->type ?: '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $cargo->uom ?: '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($cargo->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                                                Active
                                            </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-950/30 dark:text-red-300">
                                                Inactive
                                            </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @can('toggle cargos')
                                            <flux:button
                                                variant="ghost"
                                                size="sm"
                                                wire:click="toggleStatus({{ $cargo->id }})"
                                                wire:confirm="Are you sure you want to change this cargo status?"
                                                icon="{{ $cargo->is_active ? 'pause-circle' : 'play-circle' }}"
                                            >
                                                {{ $cargo->is_active ? 'Deactivate' : 'Activate' }}
                                            </flux:button>
                                        @endcan

                                        @can('edit cargos')
                                            <flux:button
                                                variant="ghost"
                                                size="sm"
                                                :href="route('cargos.edit', $cargo)"
                                                wire:navigate
                                                icon="pencil-square"
                                            >
                                                Edit
                                            </flux:button>
                                        @endcan

                                        @can('delete cargos')
                                            <flux:button
                                                variant="danger"
                                                size="sm"
                                                wire:click="delete({{ $cargo->id }})"
                                                wire:confirm="Are you sure you want to delete this cargo?"
                                                icon="trash"
                                            >
                                                Delete
                                            </flux:button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-zinc-500">
                                    No cargos found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
                    {{ $this->cargos->links() }}
                </div>
            </div>
        </div>
</section>
