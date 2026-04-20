<section class="w-full space-y-6">
    <flux:heading class="sr-only">{{ __('Cargo form') }}</flux:heading>
        <div class="space-y-6">
            <div class="flex justify-end">
                <flux:button
                    variant="ghost"
                    :href="route('cargos.index')"
                    wire:navigate
                    icon="arrow-left"
                >
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
                        <flux:input
                            wire:model="name"
                            :label="__('Cargo Name')"
                            :placeholder="__('Enter cargo name')"
                        />

                        <flux:input
                            wire:model="code"
                            :label="__('Cargo Code')"
                            :placeholder="__('Enter cargo code')"
                        />

                        <flux:input
                            wire:model="type"
                            :label="__('Cargo Type')"
                            :placeholder="__('Enter cargo type')"
                        />

                        <flux:input
                            wire:model="uom"
                            :label="__('UOM')"
                            :placeholder="__('Enter default UOM')"
                        />
                    </div>

                    <flux:textarea
                        wire:model="description"
                        :label="__('Description')"
                        rows="5"
                    />

                    <flux:checkbox
                        wire:model="is_active"
                        :label="__('Active cargo')"
                    />

                    <div class="flex justify-end gap-3">
                        <flux:button
                            variant="ghost"
                            :href="route('cargos.index')"
                            wire:navigate
                        >
                            Cancel
                        </flux:button>

                        <flux:button
                            variant="primary"
                            type="submit"
                            icon="check"
                        >
                            {{ $cargo?->exists ? __('Update Cargo') : __('Create Cargo') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
</section>
