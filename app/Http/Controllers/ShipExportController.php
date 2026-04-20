<?php

namespace App\Http\Controllers;

use App\Exports\ShipsExport;
use App\Models\Ship;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShipExportController extends Controller
{
    protected function filteredShips(Request $request)
    {
        return Ship::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('imo_number', 'like', "%{$search}%")
                        ->orWhere('call_sign', 'like', "%{$search}%")
                        ->orWhere('flag', 'like', "%{$search}%")
                        ->orWhere('owner', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->string('type')->toString());
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
            new ShipsExport(
                $request->string('search')->toString(),
                $request->string('type')->toString(),
                $request->string('status')->toString(),
            ),
            'ships.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new ShipsExport(
                $request->string('search')->toString(),
                $request->string('type')->toString(),
                $request->string('status')->toString(),
            ),
            'ships.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.ships-print', [
            'ships' => $this->filteredShips($request),
            'filters' => [
                'search' => $request->search,
                'type' => $request->type,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.ships-pdf', [
            'ships' => $this->filteredShips($request),
            'filters' => [
                'search' => $request->search,
                'type' => $request->type,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('ships.pdf');
    }
}
