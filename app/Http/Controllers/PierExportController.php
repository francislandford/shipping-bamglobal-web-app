<?php

namespace App\Http\Controllers;

use App\Exports\PiersExport;
use App\Models\Pier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PierExportController extends Controller
{
    protected function filteredPiers(Request $request)
    {
        return Pier::query()
            ->with('port')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('port', function ($portQuery) use ($search) {
                            $portQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('port'), function ($query) use ($request) {
                $query->where('port_id', $request->string('port')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->string('status')->toString() === 'active');
            })
            ->latest()
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new PiersExport(
                $request->string('search')->toString(),
                $request->string('port')->toString(),
                $request->string('status')->toString(),
            ),
            'piers.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new PiersExport(
                $request->string('search')->toString(),
                $request->string('port')->toString(),
                $request->string('status')->toString(),
            ),
            'piers.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.piers-print', [
            'piers' => $this->filteredPiers($request),
            'filters' => [
                'search' => $request->search,
                'port' => $request->port,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.piers-pdf', [
            'piers' => $this->filteredPiers($request),
            'filters' => [
                'search' => $request->search,
                'port' => $request->port,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('piers.pdf');
    }
}
