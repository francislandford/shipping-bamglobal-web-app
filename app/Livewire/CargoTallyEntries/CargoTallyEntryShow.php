<?php

namespace App\Livewire\CargoTallyEntries;

use App\Models\CargoTallyEntry;
use Livewire\Component;

class CargoTallyEntryShow extends Component
{
    public CargoTallyEntry $cargoTallyEntry;

    public function mount(CargoTallyEntry $cargoTallyEntry): void
    {
        abort_unless(auth()->user()->can('view cargo tally entries'), 403);

        $this->cargoTallyEntry = $cargoTallyEntry->load([
            'ship',
            'agency',
            'port',
            'pier',
        ]);
    }

    public function render()
    {
        return view('livewire.cargo-tally-entries.show')
            ->title('Cargo Tally Details');
    }
}
