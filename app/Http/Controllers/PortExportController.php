<?php

namespace App\Http\Controllers;

use App\Exports\PortsExport;
use App\Models\Port;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PortExportController extends Controller
{
    protected function filteredPorts(Request $request)
    {
        return Port::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('country'), function ($query) use ($request) {
                $query->where('country', $request->string('country')->toString());
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
            new PortsExport(
                $request->string('search')->toString(),
                $request->string('country')->toString(),
                $request->string('status')->toString(),
            ),
            'ports.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new PortsExport(
                $request->string('search')->toString(),
                $request->string('country')->toString(),
                $request->string('status')->toString(),
            ),
            'ports.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.ports-print', [
            'ports' => $this->filteredPorts($request),
            'filters' => [
                'search' => $request->search,
                'country' => $request->country,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.ports-pdf', [
            'ports' => $this->filteredPorts($request),
            'filters' => [
                'search' => $request->search,
                'country' => $request->country,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('ports.pdf');
    }
}
