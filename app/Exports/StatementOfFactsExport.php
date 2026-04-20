<?php

namespace App\Exports;

use App\Models\StatementOfFact;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StatementOfFactsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $ship = null,
        public ?string $port = null,
        public ?string $cargo = null,
        public ?string $status = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
    ) {
    }

    public function query()
    {
        return StatementOfFact::query()
            ->with(['user', 'ship', 'port', 'pier', 'cargoItem', 'drafts'])
            ->when($this->search, function (Builder $query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->where('cargo', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhere('loading_method_notes', 'like', "%{$search}%")
                        ->orWhereHas('ship', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('port', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('pier', fn ($piq) => $piq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('cargoItem', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->ship, fn ($q) => $q->where('ship_id', $this->ship))
            ->when($this->port, fn ($q) => $q->where('port_id', $this->port))
            ->when($this->cargo, fn ($q) => $q->where('cargo_id', $this->cargo))
            ->when($this->status, fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('report_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('report_date', '<=', $this->dateTo))
            ->orderByDesc('report_date');
    }

    public function headings(): array
    {
        return [
            'Ship',
            'Port',
            'Pier',
            'Cargo',
            'Report Date',
            'Report Time',
            'Quantity To Be Loaded',
            'Actual Total Loaded',
            'Balance To Load',
            'UOM',
            'Loaded by Grabs',
            'Loaded by Ship Loaders',
            'Total Hours Lost',
            'Created By',
            'Status',
            'Loading Method Notes',
            'Created At',
        ];
    }

    public function map($record): array
    {
        return [
            $record->ship?->name,
            $record->port?->name,
            $record->pier?->name,
            $record->cargoItem?->name ?: $record->cargo,
            optional($record->report_date)->format('Y-m-d'),
            $record->report_time,
            $record->quantity_to_be_loaded,
            $record->actual_total_loaded,
            $record->balance_to_load,
            $record->uom,
            $record->loaded_by_grabs_qty,
            $record->loaded_by_ship_loaders_qty,
            $record->total_hours_lost,
            $record->user?->name,
            $record->is_active ? 'Active' : 'Inactive',
            $record->loading_method_notes,
            optional($record->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
