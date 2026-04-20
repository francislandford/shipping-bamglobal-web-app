<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $cargoTallyEntry?->exists ? 'Edit Cargo Tally Entry' : 'Add Cargo Tally Entry' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                {{ $cargoTallyEntry?->exists
                    ? 'Update cargo tally details and operational status.'
                    : 'Create a new cargo tally record for ship loading and port operations.' }}
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('cargo-tally-entries.index')" wire:navigate icon="arrow-left">
            Back
        </flux:button>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300">
            Please fix the errors and try again.
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Operational Information</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Select the ship, voyage, agency, port, and pier for this tally record.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <flux:select wire:model.live="ship_id" label="Ship">
                    <option value="">Select ship</option>
                    @foreach ($ships as $ship)
                        <option value="{{ $ship->id }}">{{ $ship->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="voyage" label="Voyage" placeholder="Enter voyage" />

                <flux:select wire:model.live="agency_id" label="Agency">
                    <option value="">Select agency</option>
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
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

                <flux:input wire:model="load_date" type="date" label="Load Date" />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Cargo Location & Destination</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Enter hatch, compartment, and destination information.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <flux:input wire:model="hatch_no" label="Hatch No." placeholder="Enter hatch number" />
                <flux:input wire:model="compartment" label="Compartment" placeholder="Enter compartment" />
                <div class="xl:col-span-2">
                    <flux:input wire:model="destination" label="Destination" placeholder="Enter destination" />
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="sm">Cargo Details</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Record package description, quantity, and condition remarks.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <flux:textarea
                    wire:model="package_description"
                    label="Description and Quality of Packages"
                    placeholder="Enter cargo description and package quality"
                    rows="5"
                />

                <div class="space-y-6">
                    <flux:input
                        wire:model="total_quantity"
                        type="number"
                        step="0.01"
                        min="0"
                        label="Total Quantity"
                        placeholder="Enter total quantity"
                    />

                    <flux:checkbox wire:model="is_active" label="Active entry" />
                </div>
            </div>

            <div class="mt-6">
                <flux:textarea
                    wire:model="condition_remarks"
                    label="Remarks on Condition of Articles if Necessary"
                    placeholder="Enter remarks on the condition of articles"
                    rows="5"
                />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" :href="route('cargo-tally-entries.index')" wire:navigate>
                Cancel
            </flux:button>

            <flux:button variant="primary" type="submit" icon="check">
                {{ $cargoTallyEntry?->exists ? 'Update Entry' : 'Create Entry' }}
            </flux:button>
        </div>
    </form>
</div>
