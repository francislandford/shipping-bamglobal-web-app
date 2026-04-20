<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementOfFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ship_id',
        'port_id',
        'pier_id',
        'cargo_id',
        'cargo',
        'report_date',
        'report_time',
        'quantity_to_be_loaded',
        'actual_total_loaded',
        'balance_to_load',
        'uom',
        'loaded_by_grabs_qty',
        'loaded_by_ship_loaders_qty',
        'loading_method_notes',
        'total_hours_lost',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'is_active' => 'boolean',
            'quantity_to_be_loaded' => 'decimal:2',
            'actual_total_loaded' => 'decimal:2',
            'balance_to_load' => 'decimal:2',
            'loaded_by_grabs_qty' => 'decimal:2',
            'loaded_by_ship_loaders_qty' => 'decimal:2',
            'total_hours_lost' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ship()
    {
        return $this->belongsTo(Ship::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function pier()
    {
        return $this->belongsTo(Pier::class);
    }

    public function cargoItem()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function events()
    {
        return $this->hasMany(StatementOfFactEvent::class)->orderBy('sort_order');
    }

    public function loadingShifts()
    {
        return $this->hasMany(StatementOfFactLoadingShift::class);
    }

    public function tides()
    {
        return $this->hasMany(StatementOfFactTide::class);
    }

    public function delays()
    {
        return $this->hasMany(StatementOfFactDelay::class);
    }

    public function drafts()
    {
        return $this->hasMany(StatementOfFactDraft::class);
    }
}
