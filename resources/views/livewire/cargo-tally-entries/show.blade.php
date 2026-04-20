<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <flux:heading size="xl">Cargo Tally Details</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                View the full details of this cargo tally entry.
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('edit cargo tally entries')
                <flux:button
                    :href="route('cargo-tally-entries.edit', $cargoTallyEntry)"
                    wire:navigate
                    icon="pencil-square"
                >
                    Edit Entry
                </flux:button>
            @endcan

                @can('print cargo tally entries')
                    <flux:button
                        :href="route('cargo-tally-entries.print-single', $cargoTallyEntry)"
                        target="_blank"
                        icon="printer"
                    >
                        Print
                    </flux:button>

                    <flux:button
                        :href="route('cargo-tally-entries.pdf-single', $cargoTallyEntry)"
                        target="_blank"
                        icon="document-text"
                    >
                        PDF
                    </flux:button>
                @endcan

            <flux:button
                variant="ghost"
                :href="route('cargo-tally-entries.index')"
                wire:navigate
                icon="arrow-left"
            >
                Back
            </flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2 space-y-6">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-5">
                    <flux:heading size="sm">Operational Information</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Core voyage and operational details for this cargo tally entry.
                    </flux:text>
                </div>

                <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Ship</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->ship?->name ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Voyage</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->voyage ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Agency</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->agency?->name ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Port</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->port?->name ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Pier</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->pier?->name ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Load Date</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $cargoTallyEntry->load_date?->format('M d, Y') ?: '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-5">
                    <flux:heading size="sm">Cargo Location & Destination</flux:heading>
                </div>

                <dl class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Hatch No.</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->hatch_no ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Compartment</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->compartment ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Destination</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $cargoTallyEntry->destination ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-5">
                    <flux:heading size="sm">Cargo Description & Remarks</flux:heading>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                            Description and Quality of Packages
                        </div>
                        <div class="mt-2 rounded-2xl bg-zinc-50 p-4 text-sm leading-6 text-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                            {{ $cargoTallyEntry->package_description ?: 'No description provided.' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                            Remarks on Condition of Articles
                        </div>
                        <div class="mt-2 rounded-2xl bg-zinc-50 p-4 text-sm leading-6 text-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                            {{ $cargoTallyEntry->condition_remarks ?: 'No remarks provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-5">
                    <flux:heading size="sm">Summary</flux:heading>
                </div>

                <dl class="space-y-5">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Quantity</dt>
                        <dd class="mt-1 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ number_format((float) $cargoTallyEntry->total_quantity, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                        <dd class="mt-2">
                            @if ($cargoTallyEntry->is_active)
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
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Created</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $cargoTallyEntry->created_at?->format('M d, Y h:i A') ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $cargoTallyEntry->updated_at?->format('M d, Y h:i A') ?: '—' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
