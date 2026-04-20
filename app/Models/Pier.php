<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pier extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_id',
        'name',
        'code',
        'location',
        'capacity',
        'contact_person',
        'email',
        'phone',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }
}
