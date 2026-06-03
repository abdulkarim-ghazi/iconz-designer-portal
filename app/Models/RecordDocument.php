<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RecordDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_record_id',
        'uploaded_by',
        'title',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(DesignerRecord::class, 'designer_record_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function isPreviewable(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/')
            || in_array($this->mime_type, ['application/pdf', 'text/plain'], true);
    }
}
