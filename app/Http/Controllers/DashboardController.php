<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DesignerRecord;
use App\Models\RecordChange;
use App\Models\User;
use App\Models\WeeklyEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $records = DesignerRecord::query()
            ->with(['designer', 'monthlyEvaluations'])
            ->withCount(['documents', 'weeklyEntries', 'activityLogs']);

        $base = clone $records;

        return view('dashboard.index', [
            'totalDesigners' => User::where('role', 'designer')->count(),
            'totalRecords' => (clone $base)->count(),
            'openRecords' => (clone $base)->whereIn('project_status', ['new', 'in_progress', 'waiting_customer', 'sent'])->count(),
            'sentRecords' => (clone $base)->whereIn('project_status', ['sent', 'approved', 'closed'])->count(),
            'weeklyEntriesCount' => WeeklyEntry::count(),
            'activityLogsCount' => ActivityLog::count(),
            'recentRecords' => $records->with('creator')->latest()->limit(8)->get(),
            'latestChanges' => RecordChange::query()
                ->with(['record.designer', 'user'])
                ->latest()
                ->limit(10)
                ->get(),
            'designerPerformance' => DesignerRecord::query()
                ->with(['designer', 'monthlyEvaluations', 'creator'])
                ->withCount(['weeklyEntries', 'activityLogs', 'documents'])
                ->latest()
                ->limit(12)
                ->get(),
        ]);
    }
}
