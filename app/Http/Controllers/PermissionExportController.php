<?php

namespace App\Http\Controllers;

use App\Exports\PermissionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;

class PermissionExportController extends Controller
{
    protected function filteredPermissions(Request $request)
    {
        return Permission::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search')->toString() . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new PermissionsExport($request->string('search')->toString()),
            'permissions.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new PermissionsExport($request->string('search')->toString()),
            'permissions.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.permissions-print', [
            'permissions' => $this->filteredPermissions($request),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.permissions-pdf', [
            'permissions' => $this->filteredPermissions($request),
            'filters' => ['search' => $request->search],
        ]);

        return $pdf->download('permissions.pdf');
    }
}
