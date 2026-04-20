<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementOfFactLoadingShift extends Model
{
    protected $fillable = [
        'statement_of_fact_id',
        'start_datetime',
        'end_datetime',
        'quantity_loaded',
        'uom',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'quantity_loaded' => 'decimal:2',
        ];
    }

    public function statementOfFact()
    {
        return $this->belongsTo(StatementOfFact::class);
    }
}
