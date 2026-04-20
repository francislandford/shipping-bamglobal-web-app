<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementOfFactDelay extends Model
{
    protected $fillable = [
        'statement_of_fact_id',
        'start_datetime',
        'end_datetime',
        'hours_lost',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'hours_lost' => 'decimal:2',
        ];
    }

    public function statementOfFact()
    {
        return $this->belongsTo(StatementOfFact::class);
    }
}
