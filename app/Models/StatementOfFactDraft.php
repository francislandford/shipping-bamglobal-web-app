<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementOfFactDraft extends Model
{
    protected $fillable = [
        'statement_of_fact_id',
        'fwd_draft',
        'mid_draft',
        'aft_draft',
        'loading_completed_at',
        'vessel_sailed_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'fwd_draft' => 'decimal:2',
            'mid_draft' => 'decimal:2',
            'aft_draft' => 'decimal:2',
            'loading_completed_at' => 'datetime',
            'vessel_sailed_at' => 'datetime',
        ];
    }

    public function statementOfFact()
    {
        return $this->belongsTo(StatementOfFact::class);
    }
}
