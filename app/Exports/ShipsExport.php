<?php

namespace App\Exports;

use App\Models\Ship;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ShipsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        public ?string $search = null,
        public ?string $type = null,
        public ?string $status = null
    ) {}

    public function query()
    {
        return Ship::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('imo_number', 'like', '%'.$this->search.'%')
                        ->orWhere('call_sign', 'like', '%'.$this->search.'%')
                        ->orWhere('flag', 'like', '%'.$this->search.'%')
                        ->orWhere('owner', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->type, function (Builder $query) {
                $query->where('type', $this->type);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('is_active', $this->status === 'active');
            });
    }

    public function headings(): array
    {
        return [
            'Ship Name',
            'IMO Number',
            'Call Sign',
            'Flag',
            'Type',
            'Owner',
            'Status',
            'Created At',
        ];
    }

    public function map($ship): array
    {
        return [
            $ship->name,
            $ship->imo_number,
            $ship->call_sign,
            $ship->flag,
            $ship->type,
            $ship->owner,
            $ship->is_active ? 'Active' : 'Inactive',
            optional($ship->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
