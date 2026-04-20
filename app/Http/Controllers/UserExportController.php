<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserExportController extends Controller
{
    protected function filteredUsers(Request $request)
    {
        return User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->string('role')->toString());
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
            new UsersExport(
                $request->string('search')->toString(),
                $request->string('role')->toString(),
                $request->string('status')->toString(),
            ),
            'users.csv'
        );
    }

    public function xlsx(Request $request)
    {
        return Excel::download(
            new UsersExport(
                $request->string('search')->toString(),
                $request->string('role')->toString(),
                $request->string('status')->toString(),
            ),
            'users.xlsx'
        );
    }

    public function print(Request $request)
    {
        $users = $this->filteredUsers($request);

        return view('exports.users-print', [
            'users' => $users,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status,
            ],
        ]);
    }

    public function pdf(Request $request)
    {
        $users = $this->filteredUsers($request);

        $pdf = Pdf::loadView('exports.users-pdf', [
            'users' => $users,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status,
            ],
        ]);

        return $pdf->download('users.pdf');
    }
}
