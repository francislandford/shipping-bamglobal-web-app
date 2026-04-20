<?php

namespace App\Livewire\Port;

use App\Models\Port;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PortIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $country = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'country' => ['except' => ''],
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

    public function updatingCountry(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'country']);
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
        abort_unless(auth()->user()->can('delete ports'), 403);

        $port = Port::findOrFail($id);
        $port->delete();

        session()->flash('success', 'Port deleted successfully.');
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle ports'), 403);

        $port = Port::findOrFail($id);

        $port->update([
            'is_active' => ! $port->is_active,
        ]);

        session()->flash(
            'success',
            $port->is_active ? 'Port activated successfully.' : 'Port deactivated successfully.'
        );
    }

    #[Computed]
    public function countries()
    {
        return Port::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');
    }

    #[Computed]
    public function ports()
    {
        return Port::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('country', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->when($this->country !== '', function ($query) {
                $query->where('country', $this->country);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.port.port-index')
            ->title('Ports');
    }
}
