<?php

namespace App\Livewire\Ships;

use App\Models\Ship;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $type = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'type' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'type']);
        $this->sortField = 'created_at';
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
        abort_unless(auth()->user()->can('delete ships'), 403);

        $ship = Ship::findOrFail($id);
        $ship->delete();

        session()->flash('success', 'Ship deleted successfully.');
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle ships'), 403);

        $ship = Ship::findOrFail($id);

        $ship->update([
            'is_active' => ! $ship->is_active,
        ]);

        session()->flash(
            'success',
            $ship->is_active ? 'Ship activated successfully.' : 'Ship deactivated successfully.'
        );
    }

    #[Computed]
    public function shipTypes()
    {
        return Ship::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
    }

    #[Computed]
    public function ships()
    {
        return Ship::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('imo_number', 'like', '%'.$this->search.'%')
                        ->orWhere('call_sign', 'like', '%'.$this->search.'%')
                        ->orWhere('flag', 'like', '%'.$this->search.'%')
                        ->orWhere('owner', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->when($this->type !== '', function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.ships.index')
            ->title('Ships');
    }
}
