<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\CargoTallyEntry;
use Illuminate\Http\Request;

class CargoTallyEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = CargoTallyEntry::query()
            ->with([
                'ship:id,name',
                'agency:id,name',
                'port:id,name',
                'pier:id,name',
            ])
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('voyage', 'like', "%{$search}%")
                    ->orWhere('cargo_name', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('package_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ship_id')) {
            $query->where('ship_id', $request->integer('ship_id'));
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->integer('agency_id'));
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
            $query->whereDate('load_date', '>=', $request->string('from_date')->toString());
        }

        if ($request->filled('to_date')) {
            $query->whereDate('load_date', '<=', $request->string('to_date')->toString());
        }

        return response()->json([
            'data' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function show(CargoTallyEntry $cargoTallyEntry)
    {
        $cargoTallyEntry->load([
            'ship:id,name',
            'agency:id,name',
            'port:id,name',
            'pier:id,name',
        ]);

        return response()->json([
            'data' => $cargoTallyEntry,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('create cargo tally entries')) {
            abort(403, 'You are not allowed to create cargo tally entries.');
        }

        $basicInformationTouched =
            $request->filled('ship_id') ||
            $request->filled('voyage') ||
            $request->filled('agency_id') ||
            $request->filled('port_id') ||
            $request->filled('pier_id') ||
            $request->filled('cargo_id') ||
            $request->filled('uom');

        $cargoDetailsTouched =
            $request->filled('hatch_no') ||
            $request->filled('compartment') ||
            $request->filled('load_date') ||
            $request->filled('destination') ||
            $request->filled('package_description') ||
            $request->filled('total_quantity') ||
            $request->filled('condition_remarks');

        if ($basicInformationTouched && ! $user->can('cargo_tally.basic_information.edit')) {
            abort(403, 'You are not allowed to edit cargo tally basic information.');
        }

        if ($cargoDetailsTouched && ! $user->can('cargo_tally.cargo_details.edit')) {
            abort(403, 'You are not allowed to edit cargo tally details.');
        }

        $validated = $request->validate([
            'ship_id' => ['required', 'exists:ships,id'],
            'voyage' => ['nullable', 'string', 'max:255'],
            'agency_id' => ['required', 'exists:agencies,id'],
            'port_id' => ['required', 'exists:ports,id'],
            'pier_id' => ['nullable', 'exists:piers,id'],
            'cargo_id' => ['required', 'exists:cargos,id'],
            'hatch_no' => ['nullable', 'string', 'max:255'],
            'compartment' => ['nullable', 'string', 'max:255'],
            'load_date' => ['nullable', 'date'],
            'destination' => ['nullable', 'string', 'max:255'],
            'package_description' => ['nullable', 'string'],
            'total_quantity' => ['required', 'numeric', 'min:0'],
            'condition_remarks' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $cargo = Cargo::find($validated['cargo_id']);

        $record = CargoTallyEntry::create([
            'user_id' => $request->user()->id,
            'ship_id' => $validated['ship_id'],
            'voyage' => $validated['voyage'] ?? null,
            'agency_id' => $validated['agency_id'],
            'port_id' => $validated['port_id'],
            'pier_id' => $validated['pier_id'] ?? null,
            'cargo_id' => $validated['cargo_id'],
            'cargo_name' => $cargo?->name ?? $request->input('cargo_name'),
            'uom' => $cargo?->uom ?? $request->input('uom', 'WMT'),
            'hatch_no' => $validated['hatch_no'] ?? null,
            'compartment' => $validated['compartment'] ?? null,
            'load_date' => $validated['load_date'] ?? null,
            'destination' => $validated['destination'] ?? null,
            'package_description' => $validated['package_description'] ?? null,
            'total_quantity' => $validated['total_quantity'],
            'condition_remarks' => $validated['condition_remarks'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Cargo tally entry created successfully.',
            'data' => $record,
        ], 201);
    }
}
