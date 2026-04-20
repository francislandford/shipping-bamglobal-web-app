<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTallyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'ship_id',
        'voyage',
        'agency_id',
        'port_id',
        'pier_id',
        'hatch_no',
        'compartment',
        'load_date',
        'destination',
        'package_description',
        'total_quantity',
        'condition_remarks',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'load_date' => 'date',
            'total_quantity' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function ship()
    {
        return $this->belongsTo(Ship::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function pier()
    {
        return $this->belongsTo(Pier::class);
    }
}
