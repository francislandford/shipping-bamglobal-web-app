<?php

namespace App\Livewire\StatementOfFacts;

use App\Models\Cargo;
use App\Models\Port;
use App\Models\Ship;
use App\Models\StatementOfFact;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $ship = '';
    public string $port = '';
    public string $cargo = '';
    public string $status = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortField = 'report_date';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'ship' => ['except' => ''],
        'port' => ['except' => ''],
        'cargo' => ['except' => ''],
        'status' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'report_date'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingShip(): void { $this->resetPage(); }
    public function updatingPort(): void { $this->resetPage(); }
    public function updatingCargo(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'ship', 'port', 'cargo', 'status', 'dateFrom', 'dateTo']);
        $this->sortField = 'report_date';
        $this->sortDirection = 'desc';
        $this->perPage = 10;
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete statement of facts'), 403);

        StatementOfFact::findOrFail($id)->delete();

        session()->flash('success', 'Statement of facts deleted successfully.');
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle statement of facts'), 403);

        $record = StatementOfFact::findOrFail($id);
        $record->update(['is_active' => ! $record->is_active]);

        session()->flash(
            'success',
            $record->is_active
                ? 'Statement of facts activated successfully.'
                : 'Statement of facts deactivated successfully.'
        );
    }

    #[Computed]
    public function ships()
    {
        return Ship::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function ports()
    {
        return Port::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function cargos()
    {
        return Cargo::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function records()
    {
        return StatementOfFact::query()
            ->with(['user', 'ship', 'port', 'pier', 'cargoItem', 'drafts'])
            ->when($this->search !== '', function ($query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('cargo', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhere('loading_method_notes', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('cargoItem', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->cargo !== '', fn ($q) => $q->where('cargo_id', $this->cargo))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->when($this->dateFrom !== '', fn ($q) => $q->whereDate('report_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($q) => $q->whereDate('report_date', '<=', $this->dateTo))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.statement-of-facts.index')
            ->title('Statement of Facts');
    }
}
