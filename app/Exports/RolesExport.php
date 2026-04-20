<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Spatie\Permission\Models\Role;

class RolesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(public ?string $search = null)
    {
    }

    public function collection()
    {
        return Role::query()
            ->withCount('permissions')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->get()
            ->map(fn ($role) => [
                'name' => $role->name,
                'permissions_count' => $role->permissions_count,
                'created_at' => optional($role->created_at)->format('Y-m-d H:i:s'),
            ]);
    }

    public function headings(): array
    {
        return ['Role', 'Permissions Count', 'Created At'];
    }
}
