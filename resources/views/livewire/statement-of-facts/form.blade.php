<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $statementOfFact?->exists ? 'Edit Statement of Facts' : 'Add Statement of Facts' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                Capture vessel loading operations, event timeline, shift updates, tides, delays, and completion details.
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('statement-of-facts.index')" wire:navigate icon="arrow-left">
            Back
        </flux:button>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Basic Information</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <flux:select wire:model.live="ship_id" label="Ship">
                    <option value="">Select ship</option>
                    @foreach ($ships as $ship)
                        <option value="{{ $ship->id }}">{{ $ship->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="port_id" label="Port">
                    <option value="">Select port</option>
                    @foreach ($ports as $port)
                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="pier_id" label="Pier">
                    <option value="">Select pier</option>
                    @foreach ($piers as $pier)
                        <option value="{{ $pier->id }}">{{ $pier->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="cargo_id" label="Cargo">
                    <option value="">Select cargo</option>
                    @foreach ($cargos as $cargoItem)
                        <option value="{{ $cargoItem->id }}">{{ $cargoItem->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="report_date" type="date" label="Report Date" />
                <flux:input wire:model="report_time" type="time" label="Report Time" />
                <flux:input wire:model="uom" label="UOM" readonly />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Quantity Summary</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <flux:input wire:model="quantity_to_be_loaded" type="number" step="0.01" label="Quantity To Be Loaded" />
                <flux:input wire:model="actual_total_loaded" type="number" step="0.01" label="Actual Total Loaded" />
                <flux:input wire:model="balance_to_load" type="number" step="0.01" label="Balance To Load" />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="sm">Arrival / Berthing Events</flux:heading>
                <flux:button type="button" variant="ghost" wire:click="addEvent" icon="plus">
                    Add Event
                </flux:button>
            </div>

            <div class="space-y-4">
                @foreach ($events as $index => $event)
                    <div class="grid grid-cols-1 gap-4 rounded-2xl border border-zinc-200 p-4 md:grid-cols-4 dark:border-zinc-800">
                        <flux:input wire:model="events.{{ $index }}.event_date" type="date" label="Date" />
                        <flux:input wire:model="events.{{ $index }}.event_time" type="time" label="Time" />

                        <div class="md:col-span-2">
                            <flux:select wire:model="events.{{ $index }}.description" label="Description">
                                <option value="">Select event description</option>
                                @foreach ($eventDescriptionOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="md:col-span-4 flex justify-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeEvent({{ $index }})" icon="trash">
                                Remove
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="sm">Loading Shifts</flux:heading>
                <flux:button type="button" variant="ghost" wire:click="addLoadingShift" icon="plus">
                    Add Shift
                </flux:button>
            </div>

            <div class="space-y-4">
                @foreach ($loadingShifts as $index => $shift)
                    <div class="grid grid-cols-1 gap-4 rounded-2xl border border-zinc-200 p-4 md:grid-cols-4 dark:border-zinc-800">
                        <flux:input wire:model="loadingShifts.{{ $index }}.start_datetime" type="datetime-local" label="Start" />
                        <flux:input wire:model="loadingShifts.{{ $index }}.end_datetime" type="datetime-local" label="End" />
                        <flux:input wire:model="loadingShifts.{{ $index }}.quantity_loaded" type="number" step="0.01" label="Quantity Loaded" />
                        <flux:input wire:model="loadingShifts.{{ $index }}.uom" label="UOM" readonly />

                        <div class="md:col-span-4 flex justify-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeLoadingShift({{ $index }})" icon="trash">
                                Remove
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Loading Method</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <flux:input wire:model="loaded_by_grabs_qty" type="number" step="0.01" label="Loaded by Grabs" />
                <flux:input wire:model="loaded_by_ship_loaders_qty" type="number" step="0.01" label="Loaded by Ship Loaders" />
            </div>

            <div class="mt-6">
                <flux:textarea wire:model="loading_method_notes" label="Loading Method Notes" rows="4" />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="sm">High Tide Information</flux:heading>
                <flux:button type="button" variant="ghost" wire:click="addTide" icon="plus">Add Tide</flux:button>
            </div>

            <div class="space-y-4">
                @foreach ($tides as $index => $tide)
                    <div class="grid grid-cols-1 gap-4 rounded-2xl border border-zinc-200 p-4 md:grid-cols-3 dark:border-zinc-800">
                        <flux:input wire:model="tides.{{ $index }}.tide_date" type="date" label="Date" />
                        <flux:input wire:model="tides.{{ $index }}.first_high_water" type="time" label="1st High Water" />
                        <flux:input wire:model="tides.{{ $index }}.second_high_water" type="time" label="2nd High Water" />

                        <div class="md:col-span-3 flex justify-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeTide({{ $index }})" icon="trash">
                                Remove
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="sm">Delay / Time Lost Summary</flux:heading>
                <flux:button type="button" variant="ghost" wire:click="addDelay" icon="plus">Add Delay</flux:button>
            </div>

            <div class="space-y-4">
                @foreach ($delays as $index => $delay)
                    <div class="grid grid-cols-1 gap-4 rounded-2xl border border-zinc-200 p-4 md:grid-cols-4 dark:border-zinc-800">
                        <flux:input wire:model="delays.{{ $index }}.start_datetime" type="datetime-local" label="Start" />
                        <flux:input wire:model="delays.{{ $index }}.end_datetime" type="datetime-local" label="End" />
                        <flux:input wire:model="delays.{{ $index }}.hours_lost" type="number" step="0.01" label="Hours Lost" />

                        <flux:select wire:model="delays.{{ $index }}.reason" label="Reason">
                            <option value="">Select reason</option>
                            @foreach ($delayReasonOptions as $reason)
                                <option value="{{ $reason }}">{{ $reason }}</option>
                            @endforeach
                        </flux:select>

                        <div class="md:col-span-4 flex justify-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeDelay({{ $index }})" icon="trash">
                                Remove
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 max-w-sm">
                <flux:input wire:model="total_hours_lost" type="number" step="0.01" label="Total Hours Lost" />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="sm">Draft / Completion</flux:heading>
                <flux:button type="button" variant="ghost" wire:click="addDraft" icon="plus">
                    Add Draft / Completion
                </flux:button>
            </div>

            <div class="space-y-4">
                @foreach ($drafts as $index => $draft)
                    <div class="rounded-2xl border border-zinc-200 p-4 dark:border-zinc-800">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <flux:input wire:model="drafts.{{ $index }}.fwd_draft" type="number" step="0.01" label="FWD Draft" />
                            <flux:input wire:model="drafts.{{ $index }}.mid_draft" type="number" step="0.01" label="MID Draft" />
                            <flux:input wire:model="drafts.{{ $index }}.aft_draft" type="number" step="0.01" label="AFT Draft" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <flux:input wire:model="drafts.{{ $index }}.loading_completed_at" type="datetime-local" label="Loading Completed At" />
                            <flux:input wire:model="drafts.{{ $index }}.vessel_sailed_at" type="datetime-local" label="Vessel Sailed At" />
                        </div>

                        <div class="mt-4">
                            <flux:textarea wire:model="drafts.{{ $index }}.remarks" label="Remarks" rows="4" />
                        </div>

                        <div class="mt-4 flex justify-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeDraft({{ $index }})" icon="trash">
                                Remove
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <flux:checkbox wire:model="is_active" label="Active record" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" :href="route('statement-of-facts.index')" wire:navigate>
                Cancel
            </flux:button>

            <flux:button variant="primary" type="submit" icon="check">
                {{ $statementOfFact?->exists ? 'Update Record' : 'Create Record' }}
            </flux:button>
        </div>
    </form>
</div>
