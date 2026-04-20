<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $port?->exists ? 'Edit Port' : 'Add Port' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                {{ $port?->exists ? 'Update port details and operational status.' : 'Create a new port record for operations.' }}
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('ports.index')" wire:navigate icon="arrow-left">
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
                <flux:input wire:model="name" label="Port Name" placeholder="Enter port name" />

                <flux:input wire:model="code" label="Port Code" placeholder="Enter unique port code" />

                <flux:input wire:model="country" label="Country" placeholder="Enter country" />

                <flux:input wire:model="city" label="City" placeholder="Enter city" />

                <flux:input wire:model="location" label="Location" placeholder="Enter location / terminal" />

                <flux:input wire:model="contact_person" label="Contact Person" placeholder="Enter contact person's name" />

                <flux:input wire:model="email" type="email" label="Email Address" placeholder="Enter port email" />

                <flux:input wire:model="phone" label="Phone Number" placeholder="Enter phone number" />
            </div>

            <flux:textarea wire:model="notes" label="Notes" placeholder="Additional notes about this port" rows="5" />

            <div>
                <flux:checkbox wire:model="is_active" label="Active port" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('ports.index')" wire:navigate>
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="submit" icon="check">
                    {{ $port?->exists ? 'Update Port' : 'Create Port' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
