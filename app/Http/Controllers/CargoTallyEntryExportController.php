<?php

namespace App\Http\Controllers;

use App\Exports\CargoTallyEntriesExport;
use App\Models\CargoTallyEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CargoTallyEntryExportController extends Controller
{
    protected function filteredEntries(Request $request)
    {
        return CargoTallyEntry::query()
            ->with(['ship', 'agency', 'port', 'pier'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('voyage', 'like', "%{$search}%")
                        ->orWhere('hatch_no', 'like', "%{$search}%")
                        ->orWhere('compartment', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%")
                        ->orWhere('package_description', 'like', "%{$search}%")
                        ->orWhere('condition_remarks', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('agency', fn ($aq) => $aq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('ship'), fn ($q) => $q->where('ship_id', $request->string('ship')->toString()))
            ->when($request->filled('agency'), fn ($q) => $q->where('agency_id', $request->string('agency')->toString()))
            ->when($request->filled('port'), fn ($q) => $q->where('port_id', $request->string('port')->toString()))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest()
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new CargoTallyEntriesExport(
                $request->string('search')->toString(),
                $request->string('ship')->toString(),
                $request->string('agency')->toString(),
                $request->string('port')->toString(),
                $request->string('status')->toString(),
            ),
            'cargo-tally-entries.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new CargoTallyEntriesExport(
                $request->string('search')->toString(),
                $request->string('ship')->toString(),
                $request->string('agency')->toString(),
                $request->string('port')->toString(),
                $request->string('status')->toString(),
            ),
            'cargo-tally-entries.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.cargo-tally-entries-print', [
            'entries' => $this->filteredEntries($request),
            'filters' => [
                'search' => $request->search,
                'ship' => $request->ship,
                'agency' => $request->agency,
                'port' => $request->port,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.cargo-tally-entries-pdf', [
            'entries' => $this->filteredEntries($request),
            'filters' => [
                'search' => $request->search,
                'ship' => $request->ship,
                'agency' => $request->agency,
                'port' => $request->port,
                'status' => $request->status,
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('cargo-tally-entries.pdf');
    }

    public function printSingle(\App\Models\CargoTallyEntry $cargoTallyEntry)
    {
        $cargoTallyEntry->load(['ship', 'agency', 'port', 'pier']);

        return view('exports.cargo-tally-entry-single-print', [
            'entry' => $cargoTallyEntry,
        ]);
    }

    public function pdfSingle(\App\Models\CargoTallyEntry $cargoTallyEntry)
    {
        $cargoTallyEntry->load(['ship', 'agency', 'port', 'pier']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.cargo-tally-entry-single-print', [
            'entry' => $cargoTallyEntry,
        ]);

        return $pdf->download('cargo-tally-entry-' . $cargoTallyEntry->id . '.pdf');
    }
}
