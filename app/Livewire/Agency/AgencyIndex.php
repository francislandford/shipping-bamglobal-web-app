<?php

namespace App\Livewire\Agency;

use App\Models\Agency;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AgencyIndex extends Component
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

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete agencies'), 403);

        $agency = Agency::findOrFail($id);
        $agency->delete();

        session()->flash('success', 'Agency deleted successfully.');
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('toggle agencies'), 403);

        $agency = Agency::findOrFail($id);

        $agency->update([
            'is_active' => ! $agency->is_active,
        ]);

        session()->flash(
            'success',
            $agency->is_active ? 'Agency activated successfully.' : 'Agency deactivated successfully.'
        );
    }

    #[Computed]
    public function agencies()
    {
        return Agency::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('code', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_person', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.agency.index')
            ->title('Agencies');
    }
}
