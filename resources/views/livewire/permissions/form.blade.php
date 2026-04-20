<section class="w-full space-y-6">

    @include('partials.settings-heading')

    <flux:heading class="sr-only">
        {{ __('Permission settings') }}
    </flux:heading>

    <x-settings.layout
        :heading="$permissionModel?->exists ? __('Edit Permission') : __('Add Permission')"
        :subheading="$permissionModel?->exists
            ? __('Update this permission name.')
            : __('Create a new permission for the application.')"
    >

        <div class="max-w-2xl space-y-6">

            {{-- Back Button --}}
            <div class="flex justify-end">
                <flux:button
                    variant="ghost"
                    :href="route('permissions.index')"
                    wire:navigate
                    icon="arrow-left"
                >
                    {{ __('Back') }}
                </flux:button>
            </div>

            {{-- Form Card --}}
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">

                <form wire:submit="save" class="space-y-6">

                    <flux:input
                        wire:model="name"
                        :label="__('Permission Name')"
                        :placeholder="__('Enter permission name')"
                    />

                    <div class="flex justify-end gap-3">

                        <flux:button
                            variant="ghost"
                            :href="route('permissions.index')"
                            wire:navigate
                        >
                            {{ __('Cancel') }}
                        </flux:button>

                        <flux:button
                            variant="primary"
                            type="submit"
                            icon="check"
                        >
                            {{ $permissionModel?->exists
                                ? __('Update Permission')
                                : __('Create Permission') }}
                        </flux:button>

                    </div>

                </form>

            </div>

        </div>

    </x-settings.layout>

</section>
