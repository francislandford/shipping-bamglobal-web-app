<?php

namespace App\Livewire\StatementOfFacts;

use App\Models\Cargo;
use App\Models\Pier;
use App\Models\Port;
use App\Models\Ship;
use App\Models\StatementOfFact;
use Livewire\Component;

class StatementOfFactsForm extends Component
{
    public ?StatementOfFact $statementOfFact = null;

    public string $ship_id = '';
    public string $port_id = '';
    public string $pier_id = '';
    public string $cargo_id = '';
    public string $cargo = '';
    public string $report_date = '';
    public string $report_time = '';
    public string $quantity_to_be_loaded = '0';
    public string $actual_total_loaded = '0';
    public string $balance_to_load = '0';
    public string $uom = 'WMT';
    public string $loaded_by_grabs_qty = '0';
    public string $loaded_by_ship_loaders_qty = '0';
    public string $loading_method_notes = '';
    public string $total_hours_lost = '0';
    public bool $is_active = true;

    public array $events = [];
    public array $loadingShifts = [];
    public array $tides = [];
    public array $delays = [];
    public array $drafts = [];

    public function mount(?StatementOfFact $statementOfFact = null): void
    {
        $this->statementOfFact = $statementOfFact;

        if ($this->statementOfFact?->exists) {
            abort_unless(auth()->user()->can('edit statement of facts'), 403);

            $record = $this->statementOfFact->load([
                'events',
                'loadingShifts',
                'tides',
                'delays',
                'drafts',
                'cargoItem',
            ]);

            $this->ship_id = (string) $record->ship_id;
            $this->port_id = (string) $record->port_id;
            $this->pier_id = (string) ($record->pier_id ?? '');
            $this->cargo_id = (string) ($record->cargo_id ?? '');
            $this->cargo = $record->cargo ?? '';
            $this->report_date = $record->report_date?->format('Y-m-d') ?? '';
            $this->report_time = $record->report_time ?? '';
            $this->quantity_to_be_loaded = (string) $record->quantity_to_be_loaded;
            $this->actual_total_loaded = (string) $record->actual_total_loaded;
            $this->balance_to_load = (string) $record->balance_to_load;
            $this->uom = $record->uom ?? 'WMT';
            $this->loaded_by_grabs_qty = (string) $record->loaded_by_grabs_qty;
            $this->loaded_by_ship_loaders_qty = (string) $record->loaded_by_ship_loaders_qty;
            $this->loading_method_notes = $record->loading_method_notes ?? '';
            $this->total_hours_lost = (string) $record->total_hours_lost;
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
                'uom' => $item->uom ?? $this->uom,
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

            $this->drafts = $record->drafts->map(fn ($item) => [
                'fwd_draft' => $item->fwd_draft !== null ? (string) $item->fwd_draft : '',
                'mid_draft' => $item->mid_draft !== null ? (string) $item->mid_draft : '',
                'aft_draft' => $item->aft_draft !== null ? (string) $item->aft_draft : '',
                'loading_completed_at' => $item->loading_completed_at?->format('Y-m-d\TH:i') ?? '',
                'vessel_sailed_at' => $item->vessel_sailed_at?->format('Y-m-d\TH:i') ?? '',
                'remarks' => $item->remarks ?? '',
            ])->toArray();
        } else {
            abort_unless(auth()->user()->can('create statement of facts'), 403);

            $this->addEvent();
            $this->addLoadingShift();
            $this->addTide();
            $this->addDelay();
            $this->addDraft();
        }
    }

    public function updatedPortId(): void
    {
        $this->pier_id = '';
    }

    public function updatedCargoId($value): void
    {
        if (! $value) {
            $this->cargo = '';
            $this->uom = 'WMT';
            return;
        }

        $cargo = Cargo::find($value);

        if ($cargo) {
            $this->cargo = $cargo->name;
            $this->uom = $cargo->uom ?: 'WMT';

            foreach ($this->loadingShifts as $index => $shift) {
                $this->loadingShifts[$index]['uom'] = $this->uom;
            }
        }
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
            'uom' => $this->uom ?: 'WMT',
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

    public function addDraft(): void
    {
        $this->drafts[] = [
            'fwd_draft' => '',
            'mid_draft' => '',
            'aft_draft' => '',
            'loading_completed_at' => '',
            'vessel_sailed_at' => '',
            'remarks' => '',
        ];
    }

    public function removeDraft(int $index): void
    {
        unset($this->drafts[$index]);
        $this->drafts = array_values($this->drafts);
    }

    public function getEventDescriptionOptionsProperty(): array
    {
        return [
            'E.O.S.P. Arrived on Road',
            'NOR Tendered',
            'Arrived at pilot station',
            'Pilot on Board',
            'First Line Ashore',
            'Vessel All Fast (Berthed)',
            'Pilot Disembarked',
            'Boarding Party on Board',
            'Free Pratique Granted',
            'Boarding Party Disembarked',
            'Initial draft survey and holds inspection carried',
            'Stevedore on board',
            'NOR accepted',
            'Loading Commenced',
        ];
    }

    public function getDelayReasonOptionsProperty(): array
    {
        return [
            'Rain',
            'Bad Weather',
            'Equipment Breakdown',
            'Mechanical Failure',
            'Power Failure',
            'Port Congestion',
            'Labour Issue',
            'Documentation Delay',
            'Customs Delay',
            'Tide Restriction',
            'Safety Issue',
            'Waiting for Instructions',
            'Other',
        ];
    }

    public function rules(): array
    {
        return [
            'ship_id' => ['required', 'exists:ships,id'],
            'port_id' => ['required', 'exists:ports,id'],
            'pier_id' => ['nullable', 'exists:piers,id'],
            'cargo_id' => ['required', 'exists:cargos,id'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'report_date' => ['nullable', 'date'],
            'report_time' => ['nullable'],
            'quantity_to_be_loaded' => ['required', 'numeric', 'min:0'],
            'actual_total_loaded' => ['required', 'numeric', 'min:0'],
            'balance_to_load' => ['required', 'numeric', 'min:0'],
            'uom' => ['required', 'string', 'max:20'],
            'loaded_by_grabs_qty' => ['required', 'numeric', 'min:0'],
            'loaded_by_ship_loaders_qty' => ['required', 'numeric', 'min:0'],
            'loading_method_notes' => ['nullable', 'string'],
            'total_hours_lost' => ['required', 'numeric', 'min:0'],
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

            'drafts.*.fwd_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.mid_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.aft_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.loading_completed_at' => ['nullable', 'date'],
            'drafts.*.vessel_sailed_at' => ['nullable', 'date'],
            'drafts.*.remarks' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $payload = [
            'user_id' => auth()->id(),
            'ship_id' => $validated['ship_id'],
            'port_id' => $validated['port_id'],
            'pier_id' => $validated['pier_id'] ?: null,
            'cargo_id' => $validated['cargo_id'],
            'cargo' => $validated['cargo'],
            'report_date' => $validated['report_date'] ?: null,
            'report_time' => $validated['report_time'] ?: null,
            'quantity_to_be_loaded' => $validated['quantity_to_be_loaded'],
            'actual_total_loaded' => $validated['actual_total_loaded'],
            'balance_to_load' => $validated['balance_to_load'],
            'uom' => $validated['uom'],
            'loaded_by_grabs_qty' => $validated['loaded_by_grabs_qty'],
            'loaded_by_ship_loaders_qty' => $validated['loaded_by_ship_loaders_qty'],
            'loading_method_notes' => $validated['loading_method_notes'],
            'total_hours_lost' => $validated['total_hours_lost'],
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
                'uom' => $shift['uom'] ?: $this->uom,
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

        $record->drafts()->delete();
        foreach ($this->drafts as $draft) {
            $hasAnyValue =
                ($draft['fwd_draft'] ?? '') !== '' ||
                ($draft['mid_draft'] ?? '') !== '' ||
                ($draft['aft_draft'] ?? '') !== '' ||
                ($draft['loading_completed_at'] ?? '') !== '' ||
                ($draft['vessel_sailed_at'] ?? '') !== '' ||
                trim($draft['remarks'] ?? '') !== '';

            if ($hasAnyValue) {
                $record->drafts()->create([
                    'fwd_draft' => $draft['fwd_draft'] !== '' ? $draft['fwd_draft'] : null,
                    'mid_draft' => $draft['mid_draft'] !== '' ? $draft['mid_draft'] : null,
                    'aft_draft' => $draft['aft_draft'] !== '' ? $draft['aft_draft'] : null,
                    'loading_completed_at' => $draft['loading_completed_at'] ?: null,
                    'vessel_sailed_at' => $draft['vessel_sailed_at'] ?: null,
                    'remarks' => $draft['remarks'] ?: null,
                ]);
            }
        }

        session()->flash(
            'success',
            $this->statementOfFact?->exists
                ? 'Statement of facts updated successfully.'
                : 'Statement of facts created successfully.'
        );

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

    public function getCargosProperty()
    {
        return Cargo::where('is_active', true)->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.statement-of-facts.form', [
            'ships' => $this->ships,
            'ports' => $this->ports,
            'piers' => $this->piers,
            'cargos' => $this->cargos,
            'eventDescriptionOptions' => $this->eventDescriptionOptions,
            'delayReasonOptions' => $this->delayReasonOptions,
        ])->title(
            $this->statementOfFact?->exists ? 'Edit Statement of Facts' : 'Add Statement of Facts'
        );
    }
}
