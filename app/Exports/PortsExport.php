<?php

namespace App\Exports;

use App\Models\Port;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PortsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $country = null,
        public ?string $status = null
    ) {
    }

    public function query()
    {
        return Port::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('country', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->country, function (Builder $query) {
                $query->where('country', $this->country);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('is_active', $this->status === 'active');
            });
    }

    public function headings(): array
    {
        return [
            'Port Name',
            'Code',
            'Country',
            'City',
            'Location',
            'Contact Person',
            'Email',
            'Phone',
            'Notes',
            'Status',
            'Created At',
        ];
    }

    public function map($port): array
    {
        return [
            $port->name,
            $port->code,
            $port->country,
            $port->city,
            $port->location,
            $port->contact_person,
            $port->email,
            $port->phone,
            $port->notes,
            $port->is_active ? 'Active' : 'Inactive',
            optional($port->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
