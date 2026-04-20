<?php

namespace App\Http\Controllers;

use App\Exports\AgenciesExport;
use App\Models\Agency;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AgencyExportController extends Controller
{
    protected function filteredAgencies(Request $request)
    {
        return Agency::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
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
            new AgenciesExport(
                $request->string('search')->toString(),
                $request->string('status')->toString(),
            ),
            'agencies.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new AgenciesExport(
                $request->string('search')->toString(),
                $request->string('status')->toString(),
            ),
            'agencies.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.agencies-print', [
            'agencies' => $this->filteredAgencies($request),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.agencies-pdf', [
            'agencies' => $this->filteredAgencies($request),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('agencies.pdf');
    }
}
