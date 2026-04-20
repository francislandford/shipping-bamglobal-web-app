<div class="space-y-8">
    {{-- Page Header --}}
    <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                User Management
            </div>

            <div>
                <flux:heading size="xl" class="tracking-tight text-zinc-900 dark:text-white">
                    Users
                </flux:heading>

                <flux:text class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                    Manage user accounts, role assignments, access status, exports, and print-ready reports from one place.
                </flux:text>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @can('create users')
                <flux:button :href="route('users.create')" wire:navigate icon="plus">
                    Add User
                </flux:button>
            @endcan

            @can('export users')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Export
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item
                            :href="route('users.export.csv', request()->query())"
                            icon="arrow-down-tray"
                        >
                            Export CSV
                        </flux:menu.item>

                        <flux:menu.item
                            :href="route('users.export.xlsx', request()->query())"
                            icon="document-arrow-down"
                        >
                            Export Excel
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan

            @can('print users')
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" icon:trailing="chevron-down">
                        Print
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item
                            :href="route('users.print', request()->query())"
                            target="_blank"
                            icon="printer"
                        >
                            Print View
                        </flux:menu.item>

                        <flux:menu.item
                            :href="route('users.pdf', request()->query())"
                            target="_blank"
                            icon="document-text"
                        >
                            Download PDF
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endcan
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300">
            <div class="mt-0.5 rounded-full bg-emerald-100 p-1 dark:bg-emerald-900/40">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.25 7.25a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.543-6.544a1 1 0 011.415 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="font-medium">Success</p>
                <p class="mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-800 shadow-sm dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300">
            <div class="mt-0.5 rounded-full bg-red-100 p-1 dark:bg-red-900/40">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8.75-3a.75.75 0 011.5 0v3.25a.75.75 0 01-1.5 0V7zm.75 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="font-medium">Something went wrong</p>
                <p class="mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-900 dark:text-white">
                        Filters
                    </flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Refine the user list by search term, role, status, and page size.
                    </flux:text>
                </div>

                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Showing {{ $this->users->firstItem() ?? 0 }}–{{ $this->users->lastItem() ?? 0 }}
                    of {{ $this->users->total() }} users
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-5">
                <flux:input
                    wire:model.live.debounce.400ms="search"
                    label="Search"
                    placeholder="Search by name or email"
                />

                <flux:select wire:model.live="role" label="Role">
                    <option value="">All roles</option>
                    @foreach ($this->roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="status" label="Status">
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </flux:select>

                <flux:select wire:model.live="perPage" label="Rows per page">
                    <option value="10">10 rows</option>
                    <option value="25">25 rows</option>
                    <option value="50">50 rows</option>
                    <option value="100">100 rows</option>
                </flux:select>

                <div class="flex items-end">
                    <flux:button
                        variant="ghost"
                        class="w-full"
                        wire:click="resetFilters"
                        icon="arrow-path"
                    >
                        Reset Filters
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-900 dark:text-white">
                        User Directory
                    </flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        View, sort, and manage registered system users.
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('name')" class="inline-flex items-center gap-1.5 hover:text-zinc-900 dark:hover:text-white">
                            Name
                        </button>
                    </th>

                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('email')" class="inline-flex items-center gap-1.5 hover:text-zinc-900 dark:hover:text-white">
                            Email
                        </button>
                    </th>

                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Roles
                    </th>

                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('is_active')" class="inline-flex items-center gap-1.5 hover:text-zinc-900 dark:hover:text-white">
                            Status
                        </button>
                    </th>

                    <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        <button wire:click="sortBy('created_at')" class="inline-flex items-center gap-1.5 hover:text-zinc-900 dark:hover:text-white">
                            Created
                        </button>
                    </th>

                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse ($this->users as $user)
                    <tr class="transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <div class="truncate font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $user->name }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                        User ID: {{ $user->id }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 align-middle text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="break-all">{{ $user->email }}</span>
                        </td>

                        <td class="px-5 py-4 align-middle">
                            <div class="flex flex-wrap gap-2">
                                @forelse ($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                            {{ $role->name }}
                                        </span>
                                @empty
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                            No role assigned
                                        </span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-5 py-4 align-middle">
                            @if ($user->is_active)
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
                        </td>

                        <td class="px-5 py-4 align-middle text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $user->created_at?->format('M d, Y') }}</div>
                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-500">
                                {{ $user->created_at?->format('h:i A') }}
                            </div>
                        </td>

                        <td class="px-5 py-4 align-middle">
                            <div class="flex flex-wrap justify-end gap-2">
                                @can('toggle users')
                                    @if ($user->id !== auth()->id())
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            wire:click="toggleStatus({{ $user->id }})"
                                            wire:confirm="Are you sure you want to change this user's status?"
                                            icon="{{ $user->is_active ? 'pause-circle' : 'play-circle' }}"
                                        >
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </flux:button>
                                    @endif
                                @endcan

                                @can('edit users')
                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        :href="route('users.edit', $user)"
                                        wire:navigate
                                        icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                @endcan

                                @can('delete users')
                                    @if ($user->id !== auth()->id())
                                        <flux:button
                                            variant="danger"
                                            size="sm"
                                            wire:click="delete({{ $user->id }})"
                                            wire:confirm="Are you sure you want to delete this user?"
                                            icon="trash"
                                        >
                                            Delete
                                        </flux:button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="mx-auto flex max-w-md flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <svg class="h-6 w-6 text-zinc-500 dark:text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-4-4H11a4 4 0 00-4 4v2m10 0H7m10-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>

                                <h3 class="mt-4 text-base font-semibold text-zinc-900 dark:text-white">
                                    No users found
                                </h3>
                                <p class="mt-2 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                    No records matched your current filters. Try adjusting the search, role, or status filters.
                                </p>

                                <div class="mt-5">
                                    <flux:button variant="ghost" wire:click="resetFilters" icon="arrow-path">
                                        Reset Filters
                                    </flux:button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/20">
            {{ $this->users->links() }}
        </div>
    </div>
</div>
