<?php

namespace App\Livewire\StatementOfFacts;

use App\Models\StatementOfFact;
use Livewire\Component;

class Show extends Component
{
    public StatementOfFact $statementOfFact;

    public function mount(StatementOfFact $statementOfFact): void
    {
        abort_unless(auth()->user()->can('view statement of facts'), 403);

        $this->statementOfFact = $statementOfFact->load([
            'user',
            'ship',
            'port',
            'pier',
            'cargoItem',
            'events',
            'loadingShifts',
            'tides',
            'delays',
            'drafts',
        ]);
    }

    public function render()
    {
        return view('livewire.statement-of-facts.show')
            ->title('Statement of Facts Details');
    }
}
