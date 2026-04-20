<?php

namespace App\Exports;

use App\Models\Pier;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PiersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $port = null,
        public ?string $status = null
    ) {
    }

    public function query()
    {
        return Pier::query()
            ->with('port')
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhereHas('port', function ($portQuery) {
                            $portQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->port, function (Builder $query) {
                $query->where('port_id', $this->port);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('is_active', $this->status === 'active');
            });
    }

    public function headings(): array
    {
        return [
            'Pier Name',
            'Code',
            'Port',
            'Location',
            'Capacity',
            'Contact Person',
            'Email',
            'Phone',
            'Notes',
            'Status',
            'Created At',
        ];
    }

    public function map($pier): array
    {
        return [
            $pier->name,
            $pier->code,
            $pier->port?->name,
            $pier->location,
            $pier->capacity,
            $pier->contact_person,
            $pier->email,
            $pier->phone,
            $pier->notes,
            $pier->is_active ? 'Active' : 'Inactive',
            optional($pier->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
