<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementOfFactTide extends Model
{
    protected $fillable = [
        'statement_of_fact_id',
        'tide_date',
        'first_high_water',
        'second_high_water',
    ];

    protected function casts(): array
    {
        return [
            'tide_date' => 'date',
        ];
    }

    public function statementOfFact()
    {
        return $this->belongsTo(StatementOfFact::class);
    }
}
