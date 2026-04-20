<?php

namespace App\Exports;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgenciesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $status = null
    ) {
    }

    public function query()
    {
        return Agency::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function (Builder $query) {
                $query->where('is_active', $this->status === 'active');
            });
    }

    public function headings(): array
    {
        return [
            'Agency Name',
            'Code',
            'Contact Person',
            'Email',
            'Phone',
            'Address',
            'Notes',
            'Status',
            'Created At',
        ];
    }

    public function map($agency): array
    {
        return [
            $agency->name,
            $agency->code,
            $agency->contact_person,
            $agency->email,
            $agency->phone,
            $agency->address,
            $agency->notes,
            $agency->is_active ? 'Active' : 'Inactive',
            optional($agency->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
