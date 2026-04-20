<div class="space-y-8">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div>
            <flux:heading size="xl">Statement of Facts</flux:heading>
            <flux:text class="mt-2 max-w-3xl text-sm text-zinc-600 dark:text-zinc-400">
                Manage vessel loading reports, operational events, shift loading updates, tide information, delay records, draft/completion details, and activity ownership.
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('create statement of facts')
                <flux:button :href="route('statement-of-facts.create')" wire:navigate icon="plus">
                    Add Record
                </flux:button>
            @endcan

            @can('export statement of facts')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">Export</flux:button>
                    <flux:menu>
                        <flux:menu.item :href="route('statement-of-facts.export.csv', request()->query())" icon="arrow-down-tray">
                            Export CSV
                        </flux:menu.item>
                        <flux:menu.item :href="route('statement-of-facts.export.xlsx', request()->query())" icon="document-arrow-down">
                            Export Excel
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('print statement of facts')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">Print</flux:button>
                    <flux:menu>
                        <flux:menu.item :href="route('statement-of-facts.print', request()->query())" target="_blank" icon="printer">
                            Print View
                        </flux:menu.item>
                        <flux:menu.item :href="route('statement-of-facts.pdf', request()->query())" target="_blank" icon="document-text">
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

    <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-8">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    label="Search"
                    placeholder="Ship, cargo, port, user, remarks"
                />

                <flux:select wire:model.live="ship" label="Ship">
                    <option value="">All ships</option>
                    @foreach ($this->ships as $ship)
                        <option value="{{ $ship->id }}">{{ $ship->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="port" label="Port">
                    <option value="">All ports</option>
                    @foreach ($this->ports as $port)
                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="cargo" label="Cargo">
                    <option value="">All cargos</option>
                    @foreach ($this->cargos as $cargo)
                        <option value="{{ $cargo->id }}">{{ $cargo->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model.live="dateFrom" type="date" label="Date From" />
                <flux:input wire:model.live="dateTo" type="date" label="Date To" />

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

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        <button wire:click="sortBy('report_date')">Report Date</button>
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Ship / Cargo
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Port / Pier
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Planned / Actual
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Created By
                    </th>
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Status
                    </th>
                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($this->records as $record)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                            <div>{{ $record->report_date?->format('M d, Y') ?: '—' }}</div>
                            <div class="text-xs text-zinc-500">{{ $record->report_time ?: '—' }}</div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                            <div>{{ $record->ship?->name ?: '—' }}</div>
                            <div class="text-xs text-zinc-500">
                                {{ $record->cargoItem?->name ?: $record->cargo ?: '—' }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                            <div>{{ $record->port?->name ?: '—' }}</div>
                            <div class="text-xs text-zinc-500">{{ $record->pier?->name ?: '—' }}</div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                            <div>{{ number_format((float) $record->quantity_to_be_loaded, 2) }} {{ $record->uom }}</div>
                            <div class="text-xs text-zinc-500">
                                {{ number_format((float) $record->actual_total_loaded, 2) }} {{ $record->uom }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                            {{ $record->user?->name ?: '—' }}
                        </td>

                        <td class="px-5 py-4">
                            @if ($record->is_active)
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
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('statement-of-facts.show', $record)"
                                    wire:navigate
                                    icon="eye"
                                >
                                    View
                                </flux:button>

                                @can('edit statement of facts')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('statement-of-facts.edit', $record)"
                                        wire:navigate
                                        icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                @endcan

                                @can('toggle statement of facts')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        wire:click="toggleStatus({{ $record->id }})"
                                        wire:confirm="Change this record status?"
                                        icon="{{ $record->is_active ? 'pause-circle' : 'play-circle' }}"
                                    >
                                        {{ $record->is_active ? 'Deactivate' : 'Activate' }}
                                    </flux:button>
                                @endcan

                                @can('delete statement of facts')
                                    <flux:button
                                        variant="danger"
                                        size="sm"
                                        wire:click="delete({{ $record->id }})"
                                        wire:confirm="Delete this statement of facts record?"
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
                            No records found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
            {{ $this->records->links() }}
        </div>
    </div>
</div>
