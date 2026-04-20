<div class="space-y-8">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                <span class="inline-block h-2 w-2 rounded-full bg-cyan-500"></span>
                Port Registry
            </div>

            <div>
                <flux:heading size="xl" class="tracking-tight text-zinc-900 dark:text-white">
                    Ports
                </flux:heading>

                <flux:text class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                    Manage port records, contact information, operational status, exports, and print-ready reports.
                </flux:text>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @can('create ports')
                <flux:button :href="route('ports.create')" wire:navigate icon="plus">
                    Add Port
                </flux:button>
            @endcan

            @can('export ports')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Export
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('ports.export.csv', request()->query())" icon="arrow-down-tray">
                            Export CSV
                        </flux:menu.item>
                        <flux:menu.item :href="route('ports.export.xlsx', request()->query())" icon="document-arrow-down">
                            Export Excel
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('print ports')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Print
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item :href="route('ports.print', request()->query())" target="_blank" icon="printer">
                            Print View
                        </flux:menu.item>
                        <flux:menu.item :href="route('ports.pdf', request()->query())" target="_blank" icon="document-text">
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
                        Refine the port list by name, country, status, and page size.
                    </flux:text>
                </div>

                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Showing {{ $this->ports->firstItem() ?? 0 }}–{{ $this->ports->lastItem() ?? 0 }}
                    of {{ $this->ports->total() }} ports
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    label="Search"
                    placeholder="Name, code, country, city, contact"
                />

                <flux:select wire:model.live="country" label="Country">
                    <option value="">All countries</option>
                    @foreach ($this->countries as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
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
            <flux:heading size="sm">Port Directory</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                View, sort, and manage registered port records.
            </flux:text>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('name')" class="inline-flex items-center gap-1.5">Port</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('code')" class="inline-flex items-center gap-1.5">Code</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('country')" class="inline-flex items-center gap-1.5">Country / City</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Contact
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
                @forelse ($this->ports as $port)
                    <tr class="transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $port->name }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $port->location ?: 'No location provided' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $port->code ?: '—' }}
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $port->country ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $port->city ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $port->contact_person ?: '—' }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $port->email ?: $port->phone ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            @if ($port->is_active)
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
                            {{ $port->created_at?->format('M d, Y') }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                @can('toggle ports')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        wire:click="toggleStatus({{ $port->id }})"
                                        wire:confirm="Are you sure you want to change this port's status?"
                                        icon="{{ $port->is_active ? 'pause-circle' : 'play-circle' }}"
                                    >
                                        {{ $port->is_active ? 'Deactivate' : 'Activate' }}
                                    </flux:button>
                                @endcan

                                @can('edit ports')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('ports.edit', $port)"
                                        wire:navigate
                                        icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                @endcan

                                @can('delete ports')
                                    <flux:button
                                        variant="danger"
                                        size="sm"
                                        wire:click="delete({{ $port->id }})"
                                        wire:confirm="Are you sure you want to delete this port?"
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
                            No ports found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
            {{ $this->ports->links() }}
        </div>
    </div>
</div>
