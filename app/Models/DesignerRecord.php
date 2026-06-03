<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignerRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_id',
        'created_by',
        'employee_name',
        'job_title',
        'start_date',
        'manager_name',
        'trial_period',
        'current_salary',
        'proposed_raise',
        'current_month',
        'customer_name',
        'project_name',
        'project_status',
        'customer_request',
        'designer_notes',
        'manager_summary',
        'final_decision',
        'decision_date',
        'decision_reason',
        'next_plan',
    ];

    protected $casts = [
        'start_date' => 'date',
        'decision_date' => 'date',
    ];

    public function designer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function weeklyEntries(): HasMany
    {
        return $this->hasMany(WeeklyEntry::class);
    }

    public function monthlyEvaluations(): HasMany
    {
        return $this->hasMany(MonthlyEvaluation::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RecordDocument::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(RecordChange::class);
    }

    public function totalScore(): int
    {
        return (int) round($this->monthlyEvaluations->avg('total_score') ?: 0);
    }
}
