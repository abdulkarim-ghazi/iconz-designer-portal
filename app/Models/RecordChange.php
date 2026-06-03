<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_record_id',
        'user_id',
        'action',
        'summary',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(DesignerRecord::class, 'designer_record_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
