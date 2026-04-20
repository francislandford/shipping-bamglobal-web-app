<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Statement of Facts Details</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                View the full operational report for this vessel loading record.
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:button
                variant="ghost"
                :href="route('statement-of-facts.index')"
                wire:navigate
                icon="arrow-left"
            >
                Back
            </flux:button>

            @can('print statement of facts')
                <flux:button
                    :href="route('statement-of-facts.print-single', $statementOfFact)"
                    target="_blank"
                    icon="printer"
                >
                    Print
                </flux:button>

                <flux:button
                    :href="route('statement-of-facts.pdf-single', $statementOfFact)"
                    target="_blank"
                    icon="document-text"
                >
                    PDF
                </flux:button>
            @endcan

            @can('edit statement of facts')
                <flux:button
                    :href="route('statement-of-facts.edit', $statementOfFact)"
                    wire:navigate
                    icon="pencil-square"
                >
                    Edit
                </flux:button>
            @endcan
        </div>
    </div>

    {{-- Basic Information --}}
    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="mb-5">
            <flux:heading size="sm">Basic Information</flux:heading>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Ship</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->ship?->name ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Port</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->port?->name ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Pier</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->pier?->name ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Cargo</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->cargoItem?->name ?: $statementOfFact->cargo ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Report Date</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->report_date?->format('M d, Y') ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Report Time</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->report_time ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">UOM</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->uom ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Created By</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statementOfFact->user?->name ?: '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Quantity Summary --}}
    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="mb-5">
            <flux:heading size="sm">Quantity Summary</flux:heading>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Quantity To Be Loaded</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ number_format((float) $statementOfFact->quantity_to_be_loaded, 2) }} {{ $statementOfFact->uom }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Actual Total Loaded</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ number_format((float) $statementOfFact->actual_total_loaded, 2) }} {{ $statementOfFact->uom }}
                </div>
            </div>

            <div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Balance To Load</div>
                <div class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ number_format((float) $statementOfFact->balance_to_load, 2) }} {{ $statementOfFact->uom }}
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Method / Summary --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Loading Method</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">Loaded by Grabs</div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                        {{ number_format((float) $statementOfFact->loaded_by_grabs_qty, 2) }} {{ $statementOfFact->uom }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">Loaded by Ship Loaders</div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                        {{ number_format((float) $statementOfFact->loaded_by_ship_loaders_qty, 2) }} {{ $statementOfFact->uom }}
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="text-sm text-zinc-500 dark:text-zinc-400">Loading Method Notes</div>
                <div class="mt-2 rounded-2xl bg-zinc-50 p-4 text-sm text-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                    {{ $statementOfFact->loading_method_notes ?: 'No notes provided.' }}
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Operational Summary</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">Total Hours Lost</div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                        {{ number_format((float) $statementOfFact->total_hours_lost, 2) }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">Status</div>
                    <div class="mt-1">
                        @if ($statementOfFact->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-950/30 dark:text-red-300">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Draft / Completion --}}
    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="mb-5">
            <flux:heading size="sm">Draft / Completion</flux:heading>
        </div>

        <div class="space-y-4">
            @forelse ($statementOfFact->drafts as $draft)
                <div class="rounded-2xl border border-zinc-200 p-4 dark:border-zinc-800">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">FWD Draft</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $draft->fwd_draft !== null ? number_format((float) $draft->fwd_draft, 2) : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">MID Draft</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $draft->mid_draft !== null ? number_format((float) $draft->mid_draft, 2) : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">AFT Draft</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $draft->aft_draft !== null ? number_format((float) $draft->aft_draft, 2) : '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Loading Completed At</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $draft->loading_completed_at?->format('M d, Y H:i') ?: '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Vessel Sailed At</div>
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $draft->vessel_sailed_at?->format('M d, Y H:i') ?: '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Remarks</div>
                        <div class="mt-2 rounded-2xl bg-zinc-50 p-4 text-sm text-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                            {{ $draft->remarks ?: 'No remarks provided.' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-zinc-500">No draft/completion records found.</div>
            @endforelse
        </div>
    </div>

    {{-- Events / Shifts / Tides / Delays --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <flux:heading size="sm">Events</flux:heading>
            <div class="mt-4 space-y-3">
                @forelse ($statementOfFact->events as $event)
                    <div class="rounded-2xl bg-zinc-50 p-4 text-sm dark:bg-zinc-800/60">
                        {{ $event->event_date?->format('d/m/Y') ?: '—' }}
                        {{ $event->event_time ?: '' }} — {{ $event->description }}
                    </div>
                @empty
                    <div class="text-sm text-zinc-500">No events recorded.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <flux:heading size="sm">Loading Shifts</flux:heading>
            <div class="mt-4 space-y-3">
                @forelse ($statementOfFact->loadingShifts as $shift)
                    <div class="rounded-2xl bg-zinc-50 p-4 text-sm dark:bg-zinc-800/60">
                        {{ $shift->start_datetime?->format('d/m/Y H:i') ?: '—' }} to
                        {{ $shift->end_datetime?->format('d/m/Y H:i') ?: '—' }}
                        — {{ number_format((float) $shift->quantity_loaded, 2) }} {{ $shift->uom }}
                    </div>
                @empty
                    <div class="text-sm text-zinc-500">No shifts recorded.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <flux:heading size="sm">Tides</flux:heading>
            <div class="mt-4 space-y-3">
                @forelse ($statementOfFact->tides as $tide)
                    <div class="rounded-2xl bg-zinc-50 p-4 text-sm dark:bg-zinc-800/60">
                        {{ $tide->tide_date?->format('d/m/Y') ?: '—' }} —
                        1st HW: {{ $tide->first_high_water ?: '—' }},
                        2nd HW: {{ $tide->second_high_water ?: '—' }}
                    </div>
                @empty
                    <div class="text-sm text-zinc-500">No tide information recorded.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <flux:heading size="sm">Delays</flux:heading>
            <div class="mt-4 space-y-3">
                @forelse ($statementOfFact->delays as $delay)
                    <div class="rounded-2xl bg-zinc-50 p-4 text-sm dark:bg-zinc-800/60">
                        {{ $delay->start_datetime?->format('d/m/Y H:i') ?: '—' }} to
                        {{ $delay->end_datetime?->format('d/m/Y H:i') ?: '—' }}
                        — {{ number_format((float) $delay->hours_lost, 2) }} hrs
                        — {{ $delay->reason ?: '—' }}
                    </div>
                @empty
                    <div class="text-sm text-zinc-500">No delays recorded.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
