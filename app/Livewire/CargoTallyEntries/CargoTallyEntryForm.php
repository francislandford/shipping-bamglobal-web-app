<?php

namespace App\Livewire\CargoTallyEntries;

use App\Models\Agency;
use App\Models\CargoTallyEntry;
use App\Models\Pier;
use App\Models\Port;
use App\Models\Ship;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CargoTallyEntryForm extends Component
{
    public ?CargoTallyEntry $cargoTallyEntry = null;

    public string $ship_id = '';
    public string $voyage = '';
    public string $agency_id = '';
    public string $port_id = '';
    public string $pier_id = '';
    public string $hatch_no = '';
    public string $compartment = '';
    public string $load_date = '';
    public string $destination = '';
    public string $package_description = '';
    public string $total_quantity = '';
    public string $condition_remarks = '';
    public bool $is_active = true;

    public function mount(?CargoTallyEntry $cargoTallyEntry = null): void
    {
        $this->cargoTallyEntry = $cargoTallyEntry;

        if ($this->cargoTallyEntry?->exists) {
            abort_unless(auth()->user()->can('edit cargo tally entries'), 403);

            $this->ship_id = (string) ($this->cargoTallyEntry->ship_id ?? '');
            $this->voyage = $this->cargoTallyEntry->voyage ?? '';
            $this->agency_id = (string) ($this->cargoTallyEntry->agency_id ?? '');
            $this->port_id = (string) ($this->cargoTallyEntry->port_id ?? '');
            $this->pier_id = (string) ($this->cargoTallyEntry->pier_id ?? '');
            $this->hatch_no = $this->cargoTallyEntry->hatch_no ?? '';
            $this->compartment = $this->cargoTallyEntry->compartment ?? '';
            $this->load_date = $this->cargoTallyEntry->load_date?->format('Y-m-d') ?? '';
            $this->destination = $this->cargoTallyEntry->destination ?? '';
            $this->package_description = $this->cargoTallyEntry->package_description ?? '';
            $this->total_quantity = $this->cargoTallyEntry->total_quantity !== null
                ? (string) $this->cargoTallyEntry->total_quantity
                : '';
            $this->condition_remarks = $this->cargoTallyEntry->condition_remarks ?? '';
            $this->is_active = (bool) $this->cargoTallyEntry->is_active;
        } else {
            abort_unless(auth()->user()->can('create cargo tally entries'), 403);
        }
    }

    public function updatedPortId(): void
    {
        $this->pier_id = '';
    }

    public function rules(): array
    {
        return [
            'ship_id' => ['required', 'exists:ships,id'],
            'voyage' => ['required', 'string', 'max:255'],
            'agency_id' => ['required', 'exists:agencies,id'],
            'port_id' => ['required', 'exists:ports,id'],
            'pier_id' => ['nullable', Rule::exists('piers', 'id')->where(fn ($q) => $q->where('port_id', $this->port_id))],
            'hatch_no' => ['nullable', 'string', 'max:255'],
            'compartment' => ['nullable', 'string', 'max:255'],
            'load_date' => ['nullable', 'date'],
            'destination' => ['nullable', 'string', 'max:255'],
            'package_description' => ['nullable', 'string'],
            'total_quantity' => ['required', 'numeric', 'min:0'],
            'condition_remarks' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->cargoTallyEntry?->exists) {
            $this->cargoTallyEntry->update($validated);
            session()->flash('success', 'Cargo tally entry updated successfully.');
        } else {
            CargoTallyEntry::create($validated);
            session()->flash('success', 'Cargo tally entry created successfully.');
        }

        return $this->redirect(route('cargo-tally-entries.index'), navigate: true);
    }

    public function getShipsProperty()
    {
        return Ship::where('is_active', true)->orderBy('name')->get();
    }

    public function getAgenciesProperty()
    {
        return Agency::where('is_active', true)->orderBy('name')->get();
    }

    public function getPortsProperty()
    {
        return Port::where('is_active', true)->orderBy('name')->get();
    }

    public function getPiersProperty()
    {
        if (! $this->port_id) {
            return collect();
        }

        return Pier::where('is_active', true)
            ->where('port_id', $this->port_id)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.cargo-tally-entries.form', [
            'ships' => $this->ships,
            'agencies' => $this->agencies,
            'ports' => $this->ports,
            'piers' => $this->piers,
        ])->title($this->cargoTallyEntry?->exists ? 'Edit Cargo Tally Entry' : 'Add Cargo Tally Entry');
    }
}
