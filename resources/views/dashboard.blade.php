<x-layouts::app :title="__('Dashboard')">
    <div class="w-full">
        <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-2xl">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Shipping Operations Dashboard
                    </h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                        Monitor cargo tally operations, vessel loading activity, and operational performance.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @can('create cargo tally entries')
                        <a
                            href="{{ route('cargo-tally-entries.create') }}"
                            class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            New Cargo Entry
                        </a>
                    @endcan

                    @can('create statement of facts')
                        <a
                            href="{{ route('statement-of-facts.create') }}"
                            class="inline-flex items-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            New Statement of Facts
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Filters --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Date From</label>
                        <input
                            type="date"
                            wire:model.live="dateFrom"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none placeholder:text-zinc-400 focus:border-zinc-400 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Date To</label>
                        <input
                            type="date"
                            wire:model.live="dateTo"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none placeholder:text-zinc-400 focus:border-zinc-400 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Port</label>
                        <select
                            wire:model.live="port"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none focus:border-zinc-400 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                        >
                            <option value="">All Ports</option>
                            @foreach ($ports as $portItem)
                                <option value="{{ $portItem->id }}">{{ $portItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ship</label>
                        <select
                            wire:model.live="ship"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none focus:border-zinc-400 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                        >
                            <option value="">All Ships</option>
                            @foreach ($ships as $shipItem)
                                <option value="{{ $shipItem->id }}">{{ $shipItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="inline-flex w-full items-center justify-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Ships</p>
                    <h3 class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ $stats['totalShips'] ?? 0 }}
                    </h3>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Cargo Loaded Today</p>
                    <h3 class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format((float) ($stats['cargoLoadedToday'] ?? 0), 2) }}
                    </h3>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Active Ports</p>
                    <h3 class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ $stats['activePorts'] ?? 0 }}
                    </h3>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Hours Lost</p>
                    <h3 class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format((float) ($stats['hoursLostToday'] ?? 0), 2) }}
                    </h3>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid gap-6 xl:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm xl:col-span-2 dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="mb-4 text-base font-semibold text-zinc-900 dark:text-white">Monthly Cargo Loaded</h3>
                    <div class="h-[320px]">
                        <canvas id="cargoBarChart"></canvas>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="mb-4 text-base font-semibold text-zinc-900 dark:text-white">Cargo Type Distribution</h3>
                    <div class="h-[320px]">
                        <canvas id="cargoTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm xl:col-span-2 dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="mb-4 text-base font-semibold text-zinc-900 dark:text-white">Weekly Vessel Activity</h3>
                    <div class="h-[320px]">
                        <canvas id="vesselLineChart"></canvas>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="mb-4 text-base font-semibold text-zinc-900 dark:text-white">Operational Status</h3>
                    <div class="h-[320px]">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent Operations --}}
            <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Recent Operations</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-zinc-50/80 dark:bg-zinc-950/40">
                        <tr class="border-b border-zinc-200 dark:border-zinc-800">
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Vessel</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Cargo</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Port</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Quantity</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Date</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($recentOperations as $operation)
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $operation['vessel'] }}</td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $operation['cargo'] }}</td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $operation['port'] }}</td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $operation['quantity'] }}</td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $operation['status'] }}</td>
                                <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $operation['date'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-zinc-500">
                                    No recent operations found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:navigated', initDashboardCharts);
            document.addEventListener('DOMContentLoaded', initDashboardCharts);

            let cargoBarChartInstance = null;
            let cargoTypeChartInstance = null;
            let vesselLineChartInstance = null;
            let statusChartInstance = null;

            function initDashboardCharts() {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(24,24,27,0.08)';
                const labelColor = isDark ? '#d4d4d8' : '#52525b';

                const monthlyCargoChart = @json($monthlyCargoChart);
                const cargoTypeChart = @json($cargoTypeChart);
                const weeklyActivityChart = @json($weeklyActivityChart);
                const statusChart = @json($statusChart);

                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: labelColor }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: labelColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            ticks: { color: labelColor },
                            grid: { color: gridColor }
                        }
                    }
                };

                if (cargoBarChartInstance) cargoBarChartInstance.destroy();
                if (cargoTypeChartInstance) cargoTypeChartInstance.destroy();
                if (vesselLineChartInstance) vesselLineChartInstance.destroy();
                if (statusChartInstance) statusChartInstance.destroy();

                const cargoBarCtx = document.getElementById('cargoBarChart');
                if (cargoBarCtx) {
                    cargoBarChartInstance = new Chart(cargoBarCtx, {
                        type: 'bar',
                        data: {
                            labels: monthlyCargoChart.labels ?? [],
                            datasets: [{
                                label: 'WMT Loaded',
                                data: monthlyCargoChart.data ?? [],
                                borderWidth: 1,
                                borderRadius: 8
                            }]
                        },
                        options: commonOptions
                    });
                }

                const cargoTypeCtx = document.getElementById('cargoTypeChart');
                if (cargoTypeCtx) {
                    cargoTypeChartInstance = new Chart(cargoTypeCtx, {
                        type: 'doughnut',
                        data: {
                            labels: cargoTypeChart.labels ?? [],
                            datasets: [{
                                data: cargoTypeChart.data ?? [],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: labelColor }
                                }
                            }
                        }
                    });
                }

                const vesselLineCtx = document.getElementById('vesselLineChart');
                if (vesselLineCtx) {
                    vesselLineChartInstance = new Chart(vesselLineCtx, {
                        type: 'line',
                        data: {
                            labels: weeklyActivityChart.labels ?? [],
                            datasets: [
                                {
                                    label: 'Arrivals',
                                    data: weeklyActivityChart.arrivals ?? [],
                                    tension: 0.35,
                                    fill: false
                                },
                                {
                                    label: 'Load Operations',
                                    data: weeklyActivityChart.loads ?? [],
                                    tension: 0.35,
                                    fill: false
                                }
                            ]
                        },
                        options: commonOptions
                    });
                }

                const statusCtx = document.getElementById('statusChart');
                if (statusCtx) {
                    statusChartInstance = new Chart(statusCtx, {
                        type: 'pie',
                        data: {
                            labels: statusChart.labels ?? [],
                            datasets: [{
                                data: statusChart.data ?? [],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: labelColor }
                                }
                            }
                        }
                    });
                }
            }
        </script>
    </div>
</x-layouts::app>
