<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_record_id',
        'week_label',
        'project',
        'positive',
        'negative',
        'flexibility',
        'production_error',
        'manager_note',
    ];

    protected $casts = [
        'production_error' => 'boolean',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(DesignerRecord::class, 'designer_record_id');
    }
}
