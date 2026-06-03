<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'job_title',
        'start_date',
        'direct_manager',
        'trial_period',
        'current_salary',
        'proposed_raise',
        'current_month',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'current_salary' => 'decimal:2',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(DesignerRecord::class);
    }
}
