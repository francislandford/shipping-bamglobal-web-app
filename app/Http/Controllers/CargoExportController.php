<?php

namespace App\Http\Controllers;

use App\Exports\CargosExport;
use App\Models\Cargo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CargoExportController extends Controller
{
    protected function filteredCargos(Request $request)
    {
        return Cargo::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('uom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest()
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new CargosExport(
                $request->string('search')->toString(),
                $request->string('status')->toString(),
            ),
            'cargos.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new CargosExport(
                $request->string('search')->toString(),
                $request->string('status')->toString(),
            ),
            'cargos.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.cargos-print', [
            'cargos' => $this->filteredCargos($request),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.cargos-pdf', [
            'cargos' => $this->filteredCargos($request),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('cargos.pdf');
    }
}
