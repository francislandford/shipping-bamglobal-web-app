<?php

namespace App\Livewire\Cargo;

use App\Models\Cargo;
use Livewire\Component;
use Livewire\WithPagination;

class CargoIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
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

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status']);
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

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle cargos'), 403);

        $cargo = Cargo::findOrFail($id);

        $cargo->update([
            'is_active' => ! $cargo->is_active,
        ]);

        session()->flash('success', $cargo->is_active
            ? 'Cargo activated successfully.'
            : 'Cargo deactivated successfully.');
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete cargos'), 403);

        Cargo::findOrFail($id)->delete();

        session()->flash('success', 'Cargo deleted successfully.');
        $this->resetPage();
    }

    public function getCargosProperty()
    {
        return Cargo::query()
            ->when($this->search !== '', function ($query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('uom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.cargo.cargo-index')
            ->title('Cargos');
    }
}
