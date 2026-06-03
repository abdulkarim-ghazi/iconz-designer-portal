<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_record_id',
        'month_key',
        'quality_score',
        'details_score',
        'execution_score',
        'speed_score',
        'brief_score',
        'production_score',
        'followup_score',
        'teamwork_score',
        'flexibility_score',
        'total_score',
        'notes',
        'manager_answers',
    ];

    protected $casts = [
        'notes' => 'array',
        'manager_answers' => 'array',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(DesignerRecord::class, 'designer_record_id');
    }
}
