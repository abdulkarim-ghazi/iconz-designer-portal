<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Designer;
use App\Models\DesignerRecord;
use App\Models\RecordChange;
use App\Models\WeeklyEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $records = DesignerRecord::query()
            ->with(['designer', 'monthlyEvaluations'])
            ->withCount(['documents', 'weeklyEntries', 'activityLogs']);

        if ($user->isDesigner()) {
            $records->where('designer_id', $user->designer_id);
        }

        $base = clone $records;
        $designerQuery = Designer::query();
        $weeklyQuery = WeeklyEntry::query();
        $activityQuery = ActivityLog::query();
        $changesQuery = RecordChange::query()->with(['record.designer', 'user']);
        $performanceQuery = DesignerRecord::query()
            ->with(['designer', 'monthlyEvaluations', 'creator'])
            ->withCount(['weeklyEntries', 'activityLogs', 'documents']);

        if ($user->isDesigner()) {
            $designerQuery->whereKey($user->designer_id);
            $weeklyQuery->whereHas('record', fn ($query) => $query->where('designer_id', $user->designer_id));
            $activityQuery->whereHas('record', fn ($query) => $query->where('designer_id', $user->designer_id));
            $changesQuery->whereHas('record', fn ($query) => $query->where('designer_id', $user->designer_id));
            $performanceQuery->where('designer_id', $user->designer_id);
        }

        return view('dashboard.index', [
            'totalDesigners' => $designerQuery->count(),
            'totalRecords' => (clone $base)->count(),
            'openRecords' => (clone $base)->whereIn('project_status', ['new', 'in_progress', 'waiting_customer', 'sent'])->count(),
            'sentRecords' => (clone $base)->whereIn('project_status', ['sent', 'approved', 'closed'])->count(),
            'weeklyEntriesCount' => $weeklyQuery->count(),
            'activityLogsCount' => $activityQuery->count(),
            'recentRecords' => $records->with('creator')->latest()->limit(8)->get(),
            'latestChanges' => $changesQuery->latest()->limit(10)->get(),
            'designerPerformance' => $performanceQuery
                ->latest()
                ->limit(12)
                ->get(),
        ]);
    }
}
