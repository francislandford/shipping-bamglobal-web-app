<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementOfFactEvent extends Model
{
    protected $fillable = [
        'statement_of_fact_id',
        'event_date',
        'event_time',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function statementOfFact()
    {
        return $this->belongsTo(StatementOfFact::class);
    }
}
