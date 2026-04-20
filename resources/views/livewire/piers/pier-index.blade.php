<div class="space-y-8">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-500"></span>
                Pier Registry
            </div>

            <div>
                <flux:heading size="xl" class="tracking-tight text-zinc-900 dark:text-white">
                    Piers
                </flux:heading>

                <flux:text class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                    Manage pier records, related ports, operational status, exports, and print-ready reports.
                </flux:text>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @can('create piers')
                <flux:button :href="route('piers.create')" wire:navigate icon="plus">
                    Add Pier
                </flux:button>
            @endcan

            @can('export piers')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Export
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('piers.export.csv', request()->query())" icon="arrow-down-tray">
                            Export CSV
                        </flux:menu.item>
                        <flux:menu.item :href="route('piers.export.xlsx', request()->query())" icon="document-arrow-down">
                            Export Excel
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('print piers')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Print
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('piers.print', request()->query())" target="_blank" icon="printer">
                            Print View
                        </flux:menu.item>
                        <flux:menu.item :href="route('piers.pdf', request()->query())" target="_blank" icon="document-text">
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
                        Refine the pier list by name, port, status, and page size.
                    </flux:text>
                </div>

                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Showing {{ $this->piers->firstItem() ?? 0 }}–{{ $this->piers->lastItem() ?? 0 }}
                    of {{ $this->piers->total() }} piers
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    label="Search"
                    placeholder="Name, code, location, port"
                />

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

                <flux:select wire:model.live="perPage" label="Rows per page">
                    <option value="10">10 rows</option>
                    <option value="25">25 rows</option>
                    <option value="50">50 rows</option>
                    <option value="100">100 rows</option>
                </flux:select>

                <div class="flex items-end">
                    <flux:button variant="ghost" class="w-full" wire:click="resetFilters" icon="arrow-path">
                        Reset Filters
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <flux:heading size="sm">Pier Directory</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                View, sort, and manage registered pier records.
            </flux:text>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('name')" class="inline-flex items-center gap-1.5">Pier</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('code')" class="inline-flex items-center gap-1.5">Code</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Port
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Location / Capacity
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('is_active')" class="inline-flex items-center gap-1.5">Status</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('created_at')" class="inline-flex items-center gap-1.5">Created</button>
                    </th>
                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($this->piers as $pier)
                    <tr class="transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $pier->name }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $pier->contact_person ?: 'No contact person' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $pier->code ?: '—' }}
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $pier->port?->name ?: '—' }}
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $pier->location ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                Capacity: {{ $pier->capacity ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            @if ($pier->is_active)
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

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $pier->created_at?->format('M d, Y') }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                @can('toggle piers')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        wire:click="toggleStatus({{ $pier->id }})"
                                        wire:confirm="Are you sure you want to change this pier's status?"
                                        icon="{{ $pier->is_active ? 'pause-circle' : 'play-circle' }}"
                                    >
                                        {{ $pier->is_active ? 'Deactivate' : 'Activate' }}
                                    </flux:button>
                                @endcan

                                @can('edit piers')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('piers.edit', $pier)"
                                        wire:navigate
                                        icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                @endcan

                                @can('delete piers')
                                    <flux:button
                                        variant="danger"
                                        size="sm"
                                        wire:click="delete({{ $pier->id }})"
                                        wire:confirm="Are you sure you want to delete this pier?"
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
                        <td colspan="7" class="px-6 py-16 text-center text-sm text-zinc-500">
                            No piers found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
            {{ $this->piers->links() }}
        </div>
    </div>
</div>
