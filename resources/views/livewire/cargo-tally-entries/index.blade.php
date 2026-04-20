<div class="space-y-8">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                Cargo Operations
            </div>

            <div>
                <flux:heading size="xl" class="tracking-tight text-zinc-900 dark:text-white">
                    Cargo Tally Entries
                </flux:heading>

                <flux:text class="mt-2 max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                    Manage cargo tally records for ship operations, loading details, package descriptions, quantities, and condition remarks.
                </flux:text>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @can('create cargo tally entries')
                <flux:button :href="route('cargo-tally-entries.create')" wire:navigate icon="plus">
                    Add Entry
                </flux:button>
            @endcan

            @can('export cargo tally entries')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Export
                    </flux:button>
                    <flux:menu>
                        <flux:menu.item :href="route('cargo-tally-entries.export.csv', request()->query())" icon="arrow-down-tray">
                            Export CSV
                        </flux:menu.item>
                        <flux:menu.item :href="route('cargo-tally-entries.export.xlsx', request()->query())" icon="document-arrow-down">
                            Export Excel
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('print cargo tally entries')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Print
                    </flux:button>
                    <flux:menu>
                        <flux:menu.item :href="route('cargo-tally-entries.print', request()->query())" target="_blank" icon="printer">
                            Print View
                        </flux:menu.item>
                        <flux:menu.item :href="route('cargo-tally-entries.pdf', request()->query())" target="_blank" icon="document-text">
                            Download PDF
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300">
            <div>
                <p class="font-medium">Success</p>
                <p class="mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-800 shadow-sm dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300">
            <div>
                <p class="font-medium">Something went wrong</p>
                <p class="mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="sm">Filters</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Refine the tally list by ship, agency, port, status, and search keywords.
                    </flux:text>
                </div>

                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Showing {{ $this->entries->firstItem() ?? 0 }}–{{ $this->entries->lastItem() ?? 0 }}
                    of {{ $this->entries->total() }} entries
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    label="Search"
                    placeholder="Voyage, ship, port, remarks"
                />

                <flux:select wire:model.live="ship" label="Ship">
                    <option value="">All ships</option>
                    @foreach ($this->ships as $ship)
                        <option value="{{ $ship->id }}">{{ $ship->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="agency" label="Agency">
                    <option value="">All agencies</option>
                    @foreach ($this->agencies as $agency)
                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="port" label="Port">
                    <option value="">All ports</option>
                    @foreach ($this->ports as $port)
                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="status" label="Status">
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </flux:select>

                <div class="grid grid-cols-2 gap-3">
                    <flux:select wire:model.live="perPage" label="Rows">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </flux:select>

                    <div class="flex items-end">
                        <flux:button variant="ghost" class="w-full" wire:click="resetFilters" icon="arrow-path">
                            Reset
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($selectedEntries) > 0)
        <div class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ count($selectedEntries) }}</span>
                    entr{{ count($selectedEntries) > 1 ? 'ies' : 'y' }} selected
                </div>

                <div class="flex flex-wrap gap-2">
                    @can('toggle cargo tally entries')
                        <flux:button
                            variant="ghost"
                            wire:click="bulkActivate"
                            wire:confirm="Activate selected entries?"
                            icon="play-circle"
                        >
                            Activate Selected
                        </flux:button>

                        <flux:button
                            variant="ghost"
                            wire:click="bulkDeactivate"
                            wire:confirm="Deactivate selected entries?"
                            icon="pause-circle"
                        >
                            Deactivate Selected
                        </flux:button>
                    @endcan

                    @can('delete cargo tally entries')
                        <flux:button
                            variant="danger"
                            wire:click="bulkDelete"
                            wire:confirm="Delete selected entries? This action cannot be undone."
                            icon="trash"
                        >
                            Delete Selected
                        </flux:button>
                    @endcan
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <flux:heading size="sm">Cargo Tally Register</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                View, sort, and manage recorded cargo tally operations.
            </flux:text>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-5 py-4 text-left">
                        <input
                            type="checkbox"
                            wire:model.live="selectPage"
                            class="rounded border-zinc-300 text-black focus:ring-black dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-white"
                        >
                    </th>

                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('voyage')" class="inline-flex items-center gap-1.5">Voyage</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Ship / Agency
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Port / Pier
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Load Info
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Quantity
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('is_active')" class="inline-flex items-center gap-1.5">Status</button>
                    </th>
                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($this->entries as $entry)
                    <tr class="transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4">
                            <input
                                type="checkbox"
                                value="{{ $entry->id }}"
                                wire:model.live="selectedEntries"
                                class="rounded border-zinc-300 text-black focus:ring-black dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-white"
                            >
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $entry->voyage }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                Destination: {{ $entry->destination ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $entry->ship?->name ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $entry->agency?->name ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $entry->port?->name ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $entry->pier?->name ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>Date: {{ $entry->load_date?->format('M d, Y') ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                Hatch: {{ $entry->hatch_no ?: '—' }} | Compartment: {{ $entry->compartment ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ number_format((float) $entry->total_quantity, 2) }}</div>
                            <div class="mt-0.5 line-clamp-1 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $entry->package_description ?: 'No description' }}
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            @if ($entry->is_active)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-200 dark:bg-red-950/30 dark:text-red-300 dark:ring-red-900">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Inactive
                                    </span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                @can('view cargo tally entries')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('cargo-tally-entries.show', $entry)"
                                        wire:navigate
                                        icon="eye"
                                    >
                                        View
                                    </flux:button>
                                @endcan

                                @can('toggle cargo tally entries')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        wire:click="toggleStatus({{ $entry->id }})"
                                        wire:confirm="Are you sure you want to change this entry's status?"
                                        icon="{{ $entry->is_active ? 'pause-circle' : 'play-circle' }}"
                                    >
                                        {{ $entry->is_active ? 'Deactivate' : 'Activate' }}
                                    </flux:button>
                                @endcan

                                @can('edit cargo tally entries')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('cargo-tally-entries.edit', $entry)"
                                        wire:navigate
                                        icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                @endcan

                                @can('delete cargo tally entries')
                                    <flux:button
                                        variant="danger"
                                        size="sm"
                                        wire:click="delete({{ $entry->id }})"
                                        wire:confirm="Are you sure you want to delete this entry?"
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
                        <td colspan="8" class="px-6 py-16 text-center text-sm text-zinc-500">
                            No cargo tally entries found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
            {{ $this->entries->links() }}
        </div>
    </div>
</div>
