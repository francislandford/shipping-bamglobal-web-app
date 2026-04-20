<div class="flex w-full items-start gap-8 max-md:flex-col">

    {{-- Sidebar --}}
    <div class="w-full pb-4 md:w-[260px]">

        {{-- Account Settings --}}
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item :href="route('profile.edit')" wire:navigate>
                {{ __('Profile') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('user-password.edit')" wire:navigate>
                {{ __('Password') }}
            </flux:navlist.item>

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route('two-factor.show')" wire:navigate>
                    {{ __('Two-factor auth') }}
                </flux:navlist.item>
            @endif

            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>
                {{ __('Appearance') }}
            </flux:navlist.item>
        </flux:navlist>

        {{-- Divider --}}
        <div class="my-6 border-t border-zinc-200 dark:border-zinc-800"></div>

        {{-- Administration --}}
        <div>
            <flux:heading size="sm" class="mb-3 text-zinc-700 dark:text-zinc-300">
                {{ __('Administration') }}
            </flux:heading>

            <flux:navlist aria-label="{{ __('Administration') }}">

                @can('view users')
                    <flux:navlist.item
                        :href="route('users.index')"
                        :current="request()->routeIs('users.*')"
                        wire:navigate
                    >
                        {{ __('Users') }}
                    </flux:navlist.item>
                @endcan

                @can('view roles')
                    <flux:navlist.item
                        :href="route('roles.index')"
                        :current="request()->routeIs('roles.*')"
                        wire:navigate
                    >
                        {{ __('Roles') }}
                    </flux:navlist.item>
                @endcan

                @can('view permissions')
                    <flux:navlist.item
                        :href="route('permissions.index')"
                        :current="request()->routeIs('permissions.*')"
                        wire:navigate
                    >
                        {{ __('Permissions') }}
                    </flux:navlist.item>
                @endcan

            </flux:navlist>
        </div>
    </div>

    {{-- Mobile Divider --}}
    <flux:separator class="md:hidden" />

    {{-- Content Area --}}
    <div class="flex-1 self-stretch max-md:pt-6">

        {{-- Page Title --}}
        @isset($heading)
            <flux:heading>
                {{ $heading }}
            </flux:heading>
        @endisset

        {{-- Page Description --}}
        @isset($subheading)
            <flux:subheading class="mt-1">
                {{ $subheading }}
            </flux:subheading>
        @endisset

        {{-- Page Content --}}
        <div class="mt-6 w-full">
            {{ $slot }}
        </div>

    </div>

</div>
