<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Spatie\Permission\Models\Permission;

class PermissionsExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(public ?string $search = null) {}

    public function collection()
    {
        return Permission::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->get()
            ->map(fn ($permission) => [
                'name' => $permission->name,
                'created_at' => optional($permission->created_at)->format('Y-m-d H:i:s'),
            ]);
    }

    public function headings(): array
    {
        return ['Permission', 'Created At'];
    }
}
