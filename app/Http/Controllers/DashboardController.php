<?php

namespace App\Http\Controllers;

use App\Models\DesignerRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $records = DesignerRecord::query()
            ->with(['designer', 'monthlyEvaluations'])
            ->withCount('documents');

        if (! $request->user()->isAdmin()) {
            $records->where('designer_id', $request->user()->id);
        }

        $base = clone $records;

        return view('dashboard.index', [
            'totalRecords' => (clone $base)->count(),
            'openRecords' => (clone $base)->whereIn('project_status', ['new', 'in_progress', 'waiting_customer', 'sent'])->count(),
            'sentRecords' => (clone $base)->whereIn('project_status', ['sent', 'approved', 'closed'])->count(),
            'recentRecords' => $records->latest()->limit(6)->get(),
        ]);
    }
}
