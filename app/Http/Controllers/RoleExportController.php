<?php

namespace App\Http\Controllers;

use App\Exports\RolesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class RoleExportController extends Controller
{
    protected function filteredRoles(Request $request)
    {
        return Role::query()
            ->withCount('permissions')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search')->toString() . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function csv(Request $request)
    {
        return Excel::download(
            new RolesExport($request->string('search')->toString()),
            'roles.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new RolesExport($request->string('search')->toString()),
            'roles.xlsx'
        );
    }

    public function print(Request $request)
    {
        return view('exports.roles-print', [
            'roles' => $this->filteredRoles($request),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('exports.roles-pdf', [
            'roles' => $this->filteredRoles($request),
            'filters' => ['search' => $request->search],
        ]);

        return $pdf->download('roles.pdf');
    }
}
