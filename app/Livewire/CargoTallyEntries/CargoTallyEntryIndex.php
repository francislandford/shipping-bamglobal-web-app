<?php

namespace App\Livewire\CargoTallyEntries;

use App\Models\Agency;
use App\Models\CargoTallyEntry;
use App\Models\Port;
use App\Models\Ship;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CargoTallyEntryIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $ship = '';

    public string $agency = '';

    public string $port = '';

    public string $status = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 10;

    public array $selectedEntries = [];

    public bool $selectPage = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'ship' => ['except' => ''],
        'agency' => ['except' => ''],
        'port' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatingShip(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatingAgency(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatingPort(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatingStatus(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatingPerPage(): void
    {
        $this->resetPageAndSelection();
    }

    public function updatedSelectPage($value): void
    {
        if ($value) {
            $this->selectedEntries = $this->entries->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedEntries = [];
        }
    }

    public function resetPageAndSelection(): void
    {
        $this->resetPage();
        $this->selectedEntries = [];
        $this->selectPage = false;
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'ship', 'agency', 'port', 'status']);
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->perPage = 10;
        $this->resetPageAndSelection();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->selectedEntries = [];
        $this->selectPage = false;
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete cargo tally entries'), 403);

        $entry = CargoTallyEntry::findOrFail($id);
        $entry->delete();

        session()->flash('success', 'Cargo tally entry deleted successfully.');
        $this->resetPageAndSelection();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle cargo tally entries'), 403);

        $entry = CargoTallyEntry::findOrFail($id);

        $entry->update([
            'is_active' => ! $entry->is_active,
        ]);

        session()->flash(
            'success',
            $entry->is_active
                ? 'Cargo tally entry activated successfully.'
                : 'Cargo tally entry deactivated successfully.'
        );
    }

    public function bulkActivate(): void
    {
        abort_unless(auth()->user()->can('toggle cargo tally entries'), 403);

        if (empty($this->selectedEntries)) {
            session()->flash('error', 'Please select at least one entry.');

            return;
        }

        CargoTallyEntry::whereIn('id', $this->selectedEntries)->update(['is_active' => true]);

        session()->flash('success', 'Selected cargo tally entries activated successfully.');
        $this->resetPageAndSelection();
    }

    public function bulkDeactivate(): void
    {
        abort_unless(auth()->user()->can('toggle cargo tally entries'), 403);

        if (empty($this->selectedEntries)) {
            session()->flash('error', 'Please select at least one entry.');

            return;
        }

        CargoTallyEntry::whereIn('id', $this->selectedEntries)->update(['is_active' => false]);

        session()->flash('success', 'Selected cargo tally entries deactivated successfully.');
        $this->resetPageAndSelection();
    }

    public function bulkDelete(): void
    {
        abort_unless(auth()->user()->can('delete cargo tally entries'), 403);

        if (empty($this->selectedEntries)) {
            session()->flash('error', 'Please select at least one entry.');

            return;
        }

        CargoTallyEntry::whereIn('id', $this->selectedEntries)->delete();

        session()->flash('success', 'Selected cargo tally entries deleted successfully.');
        $this->resetPageAndSelection();
    }

    #[Computed]
    public function ships()
    {
        return Ship::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function agencies()
    {
        return Agency::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function ports()
    {
        return Port::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function entries()
    {
        return CargoTallyEntry::query()
            ->with(['ship', 'agency', 'port', 'pier'])
            ->when($this->search !== '', function ($query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('voyage', 'like', "%{$search}%")
                        ->orWhere('hatch_no', 'like', "%{$search}%")
                        ->orWhere('compartment', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%")
                        ->orWhere('package_description', 'like', "%{$search}%")
                        ->orWhere('condition_remarks', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('agency', fn ($aq) => $aq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->ship !== '', fn ($q) => $q->where('ship_id', $this->ship))
            ->when($this->agency !== '', fn ($q) => $q->where('agency_id', $this->agency))
            ->when($this->port !== '', fn ($q) => $q->where('port_id', $this->port))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.cargo-tally-entries.index')
            ->title('Cargo Tally Entries');
    }
}
