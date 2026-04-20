<?php

namespace App\Exports;

use App\Models\Cargo;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CargosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $status = null,
    ) {
    }

    public function query()
    {
        return Cargo::query()
            ->when($this->search, function (Builder $query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('uom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($this->status, fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->latest();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Code',
            'Type',
            'UOM',
            'Description',
            'Status',
            'Created At',
        ];
    }

    public function map($cargo): array
    {
        return [
            $cargo->name,
            $cargo->code,
            $cargo->type,
            $cargo->uom,
            $cargo->description,
            $cargo->is_active ? 'Active' : 'Inactive',
            optional($cargo->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
