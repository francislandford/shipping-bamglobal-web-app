<?php

namespace App\Exports;

use App\Models\CargoTallyEntry;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CargoTallyEntriesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $ship = null,
        public ?string $agency = null,
        public ?string $port = null,
        public ?string $status = null
    ) {
    }

    public function query()
    {
        return CargoTallyEntry::query()
            ->with(['ship', 'agency', 'port', 'pier'])
            ->when($this->search, function (Builder $query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('voyage', 'like', "%{$search}%")
                        ->orWhere('hatch_no', 'like', "%{$search}%")
                        ->orWhere('compartment', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%")
                        ->orWhere('package_description', 'like', "%{$search}%")
                        ->orWhere('condition_remarks', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('agency', fn ($aq) => $aq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->ship, fn ($q) => $q->where('ship_id', $this->ship))
            ->when($this->agency, fn ($q) => $q->where('agency_id', $this->agency))
            ->when($this->port, fn ($q) => $q->where('port_id', $this->port))
            ->when($this->status, fn ($q) => $q->where('is_active', $this->status === 'active'));
    }

    public function headings(): array
    {
        return [
            'Ship',
            'Voyage',
            'Agency',
            'Port',
            'Pier',
            'Hatch No.',
            'Compartment',
            'Load Date',
            'Destination',
            'Description and Quality of Packages',
            'Total Quantity',
            'Remarks on Condition of Articles',
            'Status',
            'Created At',
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->ship?->name,
            $entry->voyage,
            $entry->agency?->name,
            $entry->port?->name,
            $entry->pier?->name,
            $entry->hatch_no,
            $entry->compartment,
            optional($entry->load_date)->format('Y-m-d'),
            $entry->destination,
            $entry->package_description,
            $entry->total_quantity,
            $entry->condition_remarks,
            $entry->is_active ? 'Active' : 'Inactive',
            optional($entry->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
