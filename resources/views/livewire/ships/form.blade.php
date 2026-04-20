<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">
                {{ $ship?->exists ? 'Edit Ship' : 'Add Ship' }}
            </flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                {{ $ship?->exists ? 'Update ship details and operational status.' : 'Create a new ship record for operations.' }}
            </flux:text>
        </div>

        <flux:button variant="ghost" :href="route('ships.index')" wire:navigate icon="arrow-left">
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
                <flux:input wire:model="name" label="Ship Name" placeholder="Enter ship name" />

                <flux:input wire:model="imo_number" label="IMO Number" placeholder="Enter IMO number" />

                <flux:input wire:model="call_sign" label="Call Sign" placeholder="Enter call sign" />

                <flux:input wire:model="flag" label="Flag" placeholder="Enter flag country" />

                <flux:input wire:model="type" label="Ship Type" placeholder="Enter ship type" />

                <flux:input wire:model="owner" label="Owner" placeholder="Enter owner name" />
            </div>

            <div>
                <flux:checkbox wire:model="is_active" label="Active ship" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('ships.index')" wire:navigate>
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="submit" icon="check">
                    {{ $ship?->exists ? 'Update Ship' : 'Create Ship' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
