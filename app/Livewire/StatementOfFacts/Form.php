<?php

namespace App\Livewire\StatementOfFacts;

use App\Models\Pier;
use App\Models\Port;
use App\Models\Ship;
use App\Models\StatementOfFact;
use Livewire\Component;

class Form extends Component
{
    public ?StatementOfFact $statementOfFact = null;

    public string $ship_id = '';
    public string $port_id = '';
    public string $pier_id = '';
    public string $cargo = '';
    public string $report_date = '';
    public string $report_time = '';
    public string $quantity_to_be_loaded = '0';
    public string $actual_total_loaded = '0';
    public string $balance_to_load = '0';
    public string $uom = 'WMT';
    public bool $loaded_by_grabs = false;
    public bool $loaded_by_ship_loaders = false;
    public string $loading_method_notes = '';
    public string $total_hours_lost = '0';
    public string $fwd_draft = '';
    public string $mid_draft = '';
    public string $aft_draft = '';
    public string $loading_completed_at = '';
    public string $vessel_sailed_at = '';
    public string $remarks = '';
    public bool $is_active = true;

    public array $events = [];
    public array $loadingShifts = [];
    public array $tides = [];
    public array $delays = [];

    public function mount(?StatementOfFact $statementOfFact = null): void
    {
        $this->statementOfFact = $statementOfFact;

        if ($this->statementOfFact?->exists) {
            abort_unless(auth()->user()->can('edit statement of facts'), 403);

            $record = $this->statementOfFact->load(['events', 'loadingShifts', 'tides', 'delays']);

            $this->ship_id = (string) $record->ship_id;
            $this->port_id = (string) $record->port_id;
            $this->pier_id = (string) ($record->pier_id ?? '');
            $this->cargo = $record->cargo ?? '';
            $this->report_date = $record->report_date?->format('Y-m-d') ?? '';
            $this->report_time = $record->report_time ?? '';
            $this->quantity_to_be_loaded = (string) $record->quantity_to_be_loaded;
            $this->actual_total_loaded = (string) $record->actual_total_loaded;
            $this->balance_to_load = (string) $record->balance_to_load;
            $this->uom = $record->uom ?? 'WMT';
            $this->loaded_by_grabs = (bool) $record->loaded_by_grabs;
            $this->loaded_by_ship_loaders = (bool) $record->loaded_by_ship_loaders;
            $this->loading_method_notes = $record->loading_method_notes ?? '';
            $this->total_hours_lost = (string) $record->total_hours_lost;
            $this->fwd_draft = $record->fwd_draft !== null ? (string) $record->fwd_draft : '';
            $this->mid_draft = $record->mid_draft !== null ? (string) $record->mid_draft : '';
            $this->aft_draft = $record->aft_draft !== null ? (string) $record->aft_draft : '';
            $this->loading_completed_at = $record->loading_completed_at?->format('Y-m-d\TH:i') ?? '';
            $this->vessel_sailed_at = $record->vessel_sailed_at?->format('Y-m-d\TH:i') ?? '';
            $this->remarks = $record->remarks ?? '';
            $this->is_active = (bool) $record->is_active;

            $this->events = $record->events->map(fn ($item) => [
                'event_date' => $item->event_date?->format('Y-m-d') ?? '',
                'event_time' => $item->event_time ?? '',
                'description' => $item->description ?? '',
                'sort_order' => $item->sort_order ?? 0,
            ])->toArray();

            $this->loadingShifts = $record->loadingShifts->map(fn ($item) => [
                'start_datetime' => $item->start_datetime?->format('Y-m-d\TH:i') ?? '',
                'end_datetime' => $item->end_datetime?->format('Y-m-d\TH:i') ?? '',
                'quantity_loaded' => (string) $item->quantity_loaded,
                'uom' => $item->uom ?? 'WMT',
            ])->toArray();

            $this->tides = $record->tides->map(fn ($item) => [
                'tide_date' => $item->tide_date?->format('Y-m-d') ?? '',
                'first_high_water' => $item->first_high_water ?? '',
                'second_high_water' => $item->second_high_water ?? '',
            ])->toArray();

            $this->delays = $record->delays->map(fn ($item) => [
                'start_datetime' => $item->start_datetime?->format('Y-m-d\TH:i') ?? '',
                'end_datetime' => $item->end_datetime?->format('Y-m-d\TH:i') ?? '',
                'hours_lost' => (string) $item->hours_lost,
                'reason' => $item->reason ?? '',
            ])->toArray();
        } else {
            abort_unless(auth()->user()->can('create statement of facts'), 403);
            $this->addEvent();
            $this->addLoadingShift();
            $this->addTide();
            $this->addDelay();
        }
    }

    public function updatedPortId(): void
    {
        $this->pier_id = '';
    }

    public function addEvent(): void
    {
        $this->events[] = [
            'event_date' => '',
            'event_time' => '',
            'description' => '',
            'sort_order' => count($this->events),
        ];
    }

    public function removeEvent(int $index): void
    {
        unset($this->events[$index]);
        $this->events = array_values($this->events);
    }

    public function addLoadingShift(): void
    {
        $this->loadingShifts[] = [
            'start_datetime' => '',
            'end_datetime' => '',
            'quantity_loaded' => '0',
            'uom' => 'WMT',
        ];
    }

    public function removeLoadingShift(int $index): void
    {
        unset($this->loadingShifts[$index]);
        $this->loadingShifts = array_values($this->loadingShifts);
    }

    public function addTide(): void
    {
        $this->tides[] = [
            'tide_date' => '',
            'first_high_water' => '',
            'second_high_water' => '',
        ];
    }

    public function removeTide(int $index): void
    {
        unset($this->tides[$index]);
        $this->tides = array_values($this->tides);
    }

    public function addDelay(): void
    {
        $this->delays[] = [
            'start_datetime' => '',
            'end_datetime' => '',
            'hours_lost' => '0',
            'reason' => '',
        ];
    }

    public function removeDelay(int $index): void
    {
        unset($this->delays[$index]);
        $this->delays = array_values($this->delays);
    }

    public function rules(): array
    {
        return [
            'ship_id' => ['required', 'exists:ships,id'],
            'port_id' => ['required', 'exists:ports,id'],
            'pier_id' => ['nullable', 'exists:piers,id'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'report_date' => ['nullable', 'date'],
            'report_time' => ['nullable'],
            'quantity_to_be_loaded' => ['required', 'numeric', 'min:0'],
            'actual_total_loaded' => ['required', 'numeric', 'min:0'],
            'balance_to_load' => ['required', 'numeric', 'min:0'],
            'uom' => ['required', 'string', 'max:20'],
            'loaded_by_grabs' => ['boolean'],
            'loaded_by_ship_loaders' => ['boolean'],
            'loading_method_notes' => ['nullable', 'string'],
            'total_hours_lost' => ['required', 'numeric', 'min:0'],
            'fwd_draft' => ['nullable', 'numeric', 'min:0'],
            'mid_draft' => ['nullable', 'numeric', 'min:0'],
            'aft_draft' => ['nullable', 'numeric', 'min:0'],
            'loading_completed_at' => ['nullable', 'date'],
            'vessel_sailed_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'is_active' => ['boolean'],

            'events.*.event_date' => ['nullable', 'date'],
            'events.*.event_time' => ['nullable'],
            'events.*.description' => ['required', 'string', 'max:255'],

            'loadingShifts.*.start_datetime' => ['nullable', 'date'],
            'loadingShifts.*.end_datetime' => ['nullable', 'date'],
            'loadingShifts.*.quantity_loaded' => ['required', 'numeric', 'min:0'],
            'loadingShifts.*.uom' => ['required', 'string', 'max:20'],

            'tides.*.tide_date' => ['nullable', 'date'],
            'tides.*.first_high_water' => ['nullable'],
            'tides.*.second_high_water' => ['nullable'],

            'delays.*.start_datetime' => ['nullable', 'date'],
            'delays.*.end_datetime' => ['nullable', 'date'],
            'delays.*.hours_lost' => ['required', 'numeric', 'min:0'],
            'delays.*.reason' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $payload = [
            'ship_id' => $validated['ship_id'],
            'port_id' => $validated['port_id'],
            'pier_id' => $validated['pier_id'] ?: null,
            'cargo' => $validated['cargo'],
            'report_date' => $validated['report_date'] ?: null,
            'report_time' => $validated['report_time'] ?: null,
            'quantity_to_be_loaded' => $validated['quantity_to_be_loaded'],
            'actual_total_loaded' => $validated['actual_total_loaded'],
            'balance_to_load' => $validated['balance_to_load'],
            'uom' => $validated['uom'],
            'loaded_by_grabs' => $validated['loaded_by_grabs'],
            'loaded_by_ship_loaders' => $validated['loaded_by_ship_loaders'],
            'loading_method_notes' => $validated['loading_method_notes'],
            'total_hours_lost' => $validated['total_hours_lost'],
            'fwd_draft' => $validated['fwd_draft'] ?: null,
            'mid_draft' => $validated['mid_draft'] ?: null,
            'aft_draft' => $validated['aft_draft'] ?: null,
            'loading_completed_at' => $validated['loading_completed_at'] ?: null,
            'vessel_sailed_at' => $validated['vessel_sailed_at'] ?: null,
            'remarks' => $validated['remarks'],
            'is_active' => $validated['is_active'],
        ];

        if ($this->statementOfFact?->exists) {
            $record = $this->statementOfFact;
            $record->update($payload);
        } else {
            $record = StatementOfFact::create($payload);
        }

        $record->events()->delete();
        foreach ($this->events as $index => $event) {
            if (trim($event['description'] ?? '') !== '') {
                $record->events()->create([
                    'event_date' => $event['event_date'] ?: null,
                    'event_time' => $event['event_time'] ?: null,
                    'description' => $event['description'],
                    'sort_order' => $index,
                ]);
            }
        }

        $record->loadingShifts()->delete();
        foreach ($this->loadingShifts as $shift) {
            $record->loadingShifts()->create([
                'start_datetime' => $shift['start_datetime'] ?: null,
                'end_datetime' => $shift['end_datetime'] ?: null,
                'quantity_loaded' => $shift['quantity_loaded'] ?: 0,
                'uom' => $shift['uom'] ?: 'WMT',
            ]);
        }

        $record->tides()->delete();
        foreach ($this->tides as $tide) {
            $record->tides()->create([
                'tide_date' => $tide['tide_date'] ?: null,
                'first_high_water' => $tide['first_high_water'] ?: null,
                'second_high_water' => $tide['second_high_water'] ?: null,
            ]);
        }

        $record->delays()->delete();
        foreach ($this->delays as $delay) {
            $record->delays()->create([
                'start_datetime' => $delay['start_datetime'] ?: null,
                'end_datetime' => $delay['end_datetime'] ?: null,
                'hours_lost' => $delay['hours_lost'] ?: 0,
                'reason' => $delay['reason'] ?: null,
            ]);
        }

        session()->flash('success', $this->statementOfFact?->exists
            ? 'Statement of facts updated successfully.'
            : 'Statement of facts created successfully.');

        return $this->redirect(route('statement-of-facts.index'), navigate: true);
    }

    public function getShipsProperty()
    {
        return Ship::where('is_active', true)->orderBy('name')->get();
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
        return view('livewire.statement-of-facts.form', [
            'ships' => $this->ships,
            'ports' => $this->ports,
            'piers' => $this->piers,
        ])->title($this->statementOfFact?->exists ? 'Edit Statement of Facts' : 'Add Statement of Facts');
    }
}
