<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $pier?->exists ? 'Edit Pier' : 'Add Pier' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                {{ $pier?->exists ? 'Update pier details and operational status.' : 'Create a new pier record for operations.' }}
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('piers.index')" wire:navigate icon="arrow-left">
            Back
        </flux:button>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300">
            Please fix the errors and try again.
        </div>
    @endif

    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <flux:select wire:model="port_id" label="Port">
                    <option value="">Select port</option>
                    @foreach ($ports as $port)
                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="name" label="Pier Name" placeholder="Enter pier name" />

                <flux:input wire:model="code" label="Pier Code" placeholder="Enter unique pier code" />

                <flux:input wire:model="location" label="Location" placeholder="Enter location / terminal section" />

                <flux:input wire:model="capacity" type="number" label="Capacity" placeholder="Enter capacity" />

                <flux:input wire:model="contact_person" label="Contact Person" placeholder="Enter contact person's name" />

                <flux:input wire:model="email" type="email" label="Email Address" placeholder="Enter pier email" />

                <flux:input wire:model="phone" label="Phone Number" placeholder="Enter phone number" />
            </div>

            <flux:textarea wire:model="notes" label="Notes" placeholder="Additional notes about this pier" rows="5" />

            <div>
                <flux:checkbox wire:model="is_active" label="Active pier" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('piers.index')" wire:navigate>
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="submit" icon="check">
                    {{ $pier?->exists ? 'Update Pier' : 'Create Pier' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
