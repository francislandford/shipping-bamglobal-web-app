<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\StatementOfFact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementOfFactController extends Controller
{
    public function index(Request $request)
    {
        $query = StatementOfFact::query()
            ->with([
                'ship:id,name',
                'port:id,name',
                'pier:id,name',
            ])
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('cargo', 'like', "%{$search}%")
                    ->orWhere('report_date', 'like', "%{$search}%")
                    ->orWhere('report_time', 'like', "%{$search}%")
                    ->orWhere('loading_method_notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ship_id')) {
            $query->where('ship_id', $request->integer('ship_id'));
        }

        if ($request->filled('port_id')) {
            $query->where('port_id', $request->integer('port_id'));
        }

        if ($request->filled('pier_id')) {
            $query->where('pier_id', $request->integer('pier_id'));
        }

        if ($request->filled('cargo_id')) {
            $query->where('cargo_id', $request->integer('cargo_id'));
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('report_date', '>=', $request->string('from_date')->toString());
        }

        if ($request->filled('to_date')) {
            $query->whereDate('report_date', '<=', $request->string('to_date')->toString());
        }

        return response()->json([
            'data' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function show(StatementOfFact $statementOfFact)
    {
        $statementOfFact->load([
            'ship:id,name',
            'port:id,name',
            'pier:id,name',
            'events',
            'loadingShifts',
            'tides',
            'delays',
            'drafts',
        ]);

        return response()->json([
            'data' => $statementOfFact,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('create statement of facts')) {
            abort(403, 'You are not allowed to create statement of facts.');
        }

        $basicInformationTouched =
            $request->filled('ship_id') ||
            $request->filled('port_id') ||
            $request->filled('pier_id') ||
            $request->filled('cargo_id') ||
            $request->filled('report_date') ||
            $request->filled('report_time');

        $quantitySummaryTouched =
            $request->filled('quantity_to_be_loaded') ||
            $request->filled('actual_total_loaded') ||
            $request->filled('balance_to_load');

        $loadingMethodTouched =
            $request->filled('loaded_by_grabs_qty') ||
            $request->filled('loaded_by_ship_loaders_qty') ||
            $request->filled('loading_method_notes');

        if ($basicInformationTouched && ! $user->can('sof.basic_information.edit')) {
            abort(403, 'You are not allowed to edit basic information.');
        }

        if ($quantitySummaryTouched && ! $user->can('sof.quantity_summary.edit')) {
            abort(403, 'You are not allowed to edit quantity summary.');
        }

        if ($loadingMethodTouched && ! $user->can('sof.loading_method.edit')) {
            abort(403, 'You are not allowed to edit loading method.');
        }

        if ($request->filled('events') && ! $user->can('sof.events.edit')) {
            abort(403, 'You are not allowed to edit events.');
        }

        if ($request->filled('loading_shifts') && ! $user->can('sof.loading_shifts.edit')) {
            abort(403, 'You are not allowed to edit loading shifts.');
        }

        if ($request->filled('tides') && ! $user->can('sof.tides.edit')) {
            abort(403, 'You are not allowed to edit tides.');
        }

        if ($request->filled('delays') && ! $user->can('sof.delays.edit')) {
            abort(403, 'You are not allowed to edit delays.');
        }

        if ($request->filled('drafts') && ! $user->can('sof.drafts.edit')) {
            abort(403, 'You are not allowed to edit drafts.');
        }

        $validated = $request->validate([
            'ship_id' => ['required', 'exists:ships,id'],
            'port_id' => ['required', 'exists:ports,id'],
            'pier_id' => ['nullable', 'exists:piers,id'],
            'cargo_id' => ['required', 'exists:cargos,id'],
            'report_date' => ['nullable', 'date'],
            'report_time' => ['nullable'],
            'quantity_to_be_loaded' => ['nullable', 'numeric', 'min:0'],
            'actual_total_loaded' => ['nullable', 'numeric', 'min:0'],
            'balance_to_load' => ['nullable', 'numeric', 'min:0'],
            'loaded_by_grabs_qty' => ['nullable', 'numeric', 'min:0'],
            'loaded_by_ship_loaders_qty' => ['nullable', 'numeric', 'min:0'],
            'loading_method_notes' => ['nullable', 'string'],
            'total_hours_lost' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            'events' => ['nullable', 'array'],
            'events.*.event_date' => ['nullable', 'date'],
            'events.*.event_time' => ['nullable'],
            'events.*.description' => ['nullable', 'string'],

            'loading_shifts' => ['nullable', 'array'],
            'loading_shifts.*.start_datetime' => ['nullable'],
            'loading_shifts.*.end_datetime' => ['nullable'],
            'loading_shifts.*.quantity_loaded' => ['nullable', 'numeric', 'min:0'],
            'loading_shifts.*.uom' => ['nullable', 'string', 'max:50'],

            'tides' => ['nullable', 'array'],
            'tides.*.tide_date' => ['nullable', 'date'],
            'tides.*.first_high_water' => ['nullable'],
            'tides.*.second_high_water' => ['nullable'],

            'delays' => ['nullable', 'array'],
            'delays.*.start_datetime' => ['nullable'],
            'delays.*.end_datetime' => ['nullable'],
            'delays.*.hours_lost' => ['nullable', 'numeric', 'min:0'],
            'delays.*.reason' => ['nullable', 'string'],

            'drafts' => ['nullable', 'array'],
            'drafts.*.fwd_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.mid_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.aft_draft' => ['nullable', 'numeric', 'min:0'],
            'drafts.*.loading_completed_at' => ['nullable'],
            'drafts.*.vessel_sailed_at' => ['nullable'],
            'drafts.*.remarks' => ['nullable', 'string'],
        ]);

        $cargo = Cargo::find($validated['cargo_id']);

        DB::beginTransaction();

        try {
            $record = StatementOfFact::create([
                'user_id' => $user->id,
                'ship_id' => $validated['ship_id'],
                'port_id' => $validated['port_id'],
                'pier_id' => $validated['pier_id'] ?? null,
                'cargo_id' => $validated['cargo_id'],
                'cargo' => $cargo?->name,
                'report_date' => $validated['report_date'] ?? null,
                'report_time' => $validated['report_time'] ?? null,
                'quantity_to_be_loaded' => $validated['quantity_to_be_loaded'],
                'actual_total_loaded' => $validated['actual_total_loaded'],
                'balance_to_load' => $validated['balance_to_load'],
                'uom' => $cargo?->uom ?? 'WMT',
                'loaded_by_grabs_qty' => $validated['loaded_by_grabs_qty'],
                'loaded_by_ship_loaders_qty' => $validated['loaded_by_ship_loaders_qty'],
                'loading_method_notes' => $validated['loading_method_notes'] ?? null,
                'total_hours_lost' => $validated['total_hours_lost'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach (($validated['events'] ?? []) as $index => $event) {
                $record->events()->create([
                    'event_date' => $event['event_date'] ?? null,
                    'event_time' => $event['event_time'] ?? null,
                    'description' => $event['description'] ?? null,
                    'sort_order' => $index,
                ]);
            }

            foreach (($validated['loading_shifts'] ?? []) as $shift) {
                $record->loadingShifts()->create([
                    'start_datetime' => $shift['start_datetime'] ?? null,
                    'end_datetime' => $shift['end_datetime'] ?? null,
                    'quantity_loaded' => $shift['quantity_loaded'] ?? 0,
                    'uom' => $shift['uom'] ?? ($cargo?->uom ?? 'WMT'),
                ]);
            }

            foreach (($validated['tides'] ?? []) as $tide) {
                $record->tides()->create([
                    'tide_date' => $tide['tide_date'] ?? null,
                    'first_high_water' => $tide['first_high_water'] ?? null,
                    'second_high_water' => $tide['second_high_water'] ?? null,
                ]);
            }

            foreach (($validated['delays'] ?? []) as $delay) {
                $record->delays()->create([
                    'start_datetime' => $delay['start_datetime'] ?? null,
                    'end_datetime' => $delay['end_datetime'] ?? null,
                    'hours_lost' => $delay['hours_lost'] ?? 0,
                    'reason' => $delay['reason'] ?? null,
                ]);
            }

            foreach (($validated['drafts'] ?? []) as $draft) {
                $record->drafts()->create([
                    'fwd_draft' => $draft['fwd_draft'] ?? null,
                    'mid_draft' => $draft['mid_draft'] ?? null,
                    'aft_draft' => $draft['aft_draft'] ?? null,
                    'loading_completed_at' => $draft['loading_completed_at'] ?? null,
                    'vessel_sailed_at' => $draft['vessel_sailed_at'] ?? null,
                    'remarks' => $draft['remarks'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Statement of facts created successfully.',
                'data' => $record->load(['events', 'loadingShifts', 'tides', 'delays', 'drafts']),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
