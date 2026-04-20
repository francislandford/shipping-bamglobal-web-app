<?php

namespace App\Livewire\Piers;

use App\Models\Pier;
use App\Models\Port;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PierIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $port = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'port' => ['except' => ''],
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

    public function updatingPort(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'port']);
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
        abort_unless(auth()->user()->can('delete piers'), 403);

        $pier = Pier::findOrFail($id);
        $pier->delete();

        session()->flash('success', 'Pier deleted successfully.');
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle piers'), 403);

        $pier = Pier::findOrFail($id);

        $pier->update([
            'is_active' => ! $pier->is_active,
        ]);

        session()->flash(
            'success',
            $pier->is_active ? 'Pier activated successfully.' : 'Pier deactivated successfully.'
        );
    }

    #[Computed]
    public function ports()
    {
        return Port::query()
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function piers()
    {
        return Pier::query()
            ->with('port')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('code', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_person', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhereHas('port', function ($portQuery) {
                            $portQuery->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->when($this->port !== '', function ($query) {
                $query->where('port_id', $this->port);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.piers.pier-index')
            ->title('Piers');
    }
}
