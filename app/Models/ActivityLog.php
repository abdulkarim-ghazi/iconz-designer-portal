<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_record_id',
        'type',
        'project',
        'note',
        'impact',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'date',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(DesignerRecord::class, 'designer_record_id');
    }
}
