<?php

namespace App\Http\Controllers;

use App\Exports\StatementOfFactsExport;
use App\Models\StatementOfFact;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatementOfFactExportController extends Controller
{
    protected function filteredRecords(Request $request)
    {
        return StatementOfFact::query()
            ->with(['user', 'ship', 'port', 'pier', 'cargoItem', 'events', 'loadingShifts', 'tides', 'delays', 'drafts'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('cargo', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhere('loading_method_notes', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('cargoItem', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('ship'), fn ($q) => $q->where('ship_id', $request->string('ship')->toString()))
            ->when($request->filled('port'), fn ($q) => $q->where('port_id', $request->string('port')->toString()))
            ->when($request->filled('cargo'), fn ($q) => $q->where('cargo_id', $request->string('cargo')->toString()))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->string('status')->toString() === 'active'))
            ->when($request->filled('dateFrom'), fn ($q) => $q->whereDate('report_date', '>=', $request->string('dateFrom')->toString()))
            ->when($request->filled('dateTo'), fn ($q) => $q->whereDate('report_date', '<=', $request->string('dateTo')->toString()))
            ->orderByDesc('report_date')
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new StatementOfFactsExport(
                $request->string('search')->toString(),
                $request->string('ship')->toString(),
                $request->string('port')->toString(),
                $request->string('cargo')->toString(),
                $request->string('status')->toString(),
                $request->string('dateFrom')->toString(),
                $request->string('dateTo')->toString(),
            ),
            'statement-of-facts.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new StatementOfFactsExport(
                $request->string('search')->toString(),
                $request->string('ship')->toString(),
                $request->string('port')->toString(),
                $request->string('cargo')->toString(),
                $request->string('status')->toString(),
                $request->string('dateFrom')->toString(),
                $request->string('dateTo')->toString(),
            ),
            'statement-of-facts.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.statement-of-facts-print', [
            'records' => $this->filteredRecords($request),
            'filters' => [
                'search' => $request->search,
                'ship' => $request->ship,
                'port' => $request->port,
                'cargo' => $request->cargo,
                'status' => $request->status,
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.statement-of-facts-pdf', [
            'records' => $this->filteredRecords($request),
            'filters' => [
                'search' => $request->search,
                'ship' => $request->ship,
                'port' => $request->port,
                'cargo' => $request->cargo,
                'status' => $request->status,
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('statement-of-facts.pdf');
    }

    public function printSingle(StatementOfFact $statementOfFact)
    {
        $statementOfFact->load([
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

        return view('exports.statement-of-facts-single-print', [
            'record' => $statementOfFact,
        ]);
    }

    public function pdfSingle(StatementOfFact $statementOfFact)
    {
        $statementOfFact->load([
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

        $pdf = Pdf::loadView('exports.statement-of-facts-single-print', [
            'record' => $statementOfFact,
        ]);

        return $pdf->download('statement-of-facts-' . $statementOfFact->id . '.pdf');
    }
}
