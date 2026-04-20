<?php

namespace App\Livewire;

use App\Models\CargoTallyEntry;
use App\Models\Port;
use App\Models\Ship;
use App\Models\StatementOfFact;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $port = '';
    public string $ship = '';

    public array $stats = [];
    public array $monthlyCargoChart = [];
    public array $cargoTypeChart = [];
    public array $weeklyActivityChart = [];
    public array $statusChart = [];
    public array $recentOperations = [];

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'port' => ['except' => ''],
        'ship' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->loadDashboard();
    }

    public function updatedDateFrom(): void
    {
        $this->loadDashboard();
    }

    public function updatedDateTo(): void
    {
        $this->loadDashboard();
    }

    public function updatedPort(): void
    {
        $this->loadDashboard();
    }

    public function updatedShip(): void
    {
        $this->loadDashboard();
    }

    public function resetFilters(): void
    {
        $this->reset(['dateFrom', 'dateTo', 'port', 'ship']);
        $this->loadDashboard();
    }

    protected function loadDashboard(): void
    {
        $this->loadStats();
        $this->loadMonthlyCargoChart();
        $this->loadCargoTypeChart();
        $this->loadWeeklyActivityChart();
        $this->loadStatusChart();
        $this->loadRecentOperations();
        $this->dispatchChartUpdate();
    }

    protected function dispatchChartUpdate(): void
    {
        $this->dispatch('dashboard-charts-updated', [
            'monthlyCargoChart' => $this->monthlyCargoChart,
            'cargoTypeChart' => $this->cargoTypeChart,
            'weeklyActivityChart' => $this->weeklyActivityChart,
            'statusChart' => $this->statusChart,
        ]);
    }

    protected function statementOfFactsQuery()
    {
        return StatementOfFact::query()
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate('report_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate('report_date', '<=', $this->dateTo))
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship));
    }

    protected function cargoTallyQuery()
    {
        return CargoTallyEntry::query()
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate('load_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate('load_date', '<=', $this->dateTo))
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship));
    }

    protected function loadStats(): void
    {
        $today = now()->toDateString();

        $totalShips = Ship::query()
            ->when($this->ship !== '', fn ($q) => $q->where('id', $this->ship))
            ->count();

        $activePorts = Port::query()
            ->where('is_active', true)
            ->when($this->port !== '', fn ($q) => $q->where('id', $this->port))
            ->count();

        $cargoLoadedToday = (float) CargoTallyEntry::query()
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
            ->whereDate('load_date', $today)
            ->sum('total_quantity');

        $hoursLostToday = (float) StatementOfFact::query()
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
            ->whereDate('report_date', $today)
            ->sum('total_hours_lost');

        $this->stats = [
            'totalShips' => $totalShips,
            'cargoLoadedToday' => round($cargoLoadedToday, 2),
            'activePorts' => $activePorts,
            'hoursLostToday' => round($hoursLostToday, 2),
        ];
    }

    protected function loadMonthlyCargoChart(): void
    {
        $start = now()->subMonths(5)->startOfMonth();

        $rows = CargoTallyEntry::query()
            ->selectRaw('YEAR(load_date) as year, MONTH(load_date) as month, SUM(total_quantity) as total')
            ->whereNotNull('load_date')
            ->whereDate('load_date', '>=', $start)
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate('load_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate('load_date', '<=', $this->dateTo))
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
            ->groupByRaw('YEAR(load_date), MONTH(load_date)')
            ->orderByRaw('YEAR(load_date), MONTH(load_date)')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths(5 - $i);
            $labels[] = $date->format('M');

            $row = $rows->first(function ($item) use ($date) {
                return (int) $item->year === $date->year && (int) $item->month === $date->month;
            });

            $data[] = $row ? round((float) $row->total, 2) : 0;
        }

        $this->monthlyCargoChart = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function loadCargoTypeChart(): void
    {
        $rows = $this->statementOfFactsQuery()
            ->select('cargo', DB::raw('COUNT(*) as total'))
            ->whereNotNull('cargo')
            ->where('cargo', '!=', '')
            ->groupBy('cargo')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $this->cargoTypeChart = [
            'labels' => $rows->pluck('cargo')->values()->toArray(),
            'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->values()->toArray(),
        ];
    }

    protected function loadWeeklyActivityChart(): void
    {
        $labels = [];
        $arrivals = [];
        $loads = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = now()->subDays($i)->format('D');

            $arrivals[] = StatementOfFact::query()
                ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
                ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
                ->whereDate('report_date', $date)
                ->count();

            $loads[] = CargoTallyEntry::query()
                ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
                ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
                ->whereDate('load_date', $date)
                ->count();
        }

        $this->weeklyActivityChart = [
            'labels' => $labels,
            'arrivals' => $arrivals,
            'loads' => $loads,
        ];
    }

    protected function loadStatusChart(): void
    {
        $query = $this->statementOfFactsQuery();

        $completed = (clone $query)->whereNotNull('loading_completed_at')->count();
        $loading = (clone $query)->whereNull('loading_completed_at')->where('is_active', true)->count();
        $inactive = (clone $query)->where('is_active', false)->count();
        $delayed = (clone $query)->where('total_hours_lost', '>', 0)->count();

        $this->statusChart = [
            'labels' => ['Completed', 'Loading', 'Inactive', 'Delayed'],
            'data' => [$completed, $loading, $inactive, $delayed],
        ];
    }

    protected function loadRecentOperations(): void
    {
        $records = $this->statementOfFactsQuery()
            ->with(['ship', 'port'])
            ->latest('report_date')
            ->latest('created_at')
            ->limit(8)
            ->get();

        $this->recentOperations = $records->map(function ($record) {
            $status = 'Pending';

            if ($record->loading_completed_at) {
                $status = 'Completed';
            } elseif ($record->is_active) {
                $status = 'Loading';
            } elseif (! $record->is_active) {
                $status = 'Inactive';
            }

            return [
                'id' => $record->id,
                'vessel' => $record->ship?->name ?: '—',
                'cargo' => $record->cargo ?: '—',
                'port' => $record->port?->name ?: '—',
                'quantity' => number_format((float) $record->actual_total_loaded, 2) . ' ' . ($record->uom ?: 'WMT'),
                'status' => $status,
                'date' => $record->report_date?->format('d M Y') ?: '—',
            ];
        })->toArray();
    }

    public function getShipsProperty()
    {
        return Ship::where('is_active', true)->orderBy('name')->get();
    }

    public function getPortsProperty()
    {
        return Port::where('is_active', true)->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'ships' => $this->ships,
            'ports' => $this->ports,
        ])->title('Dashboard');
    }
}
