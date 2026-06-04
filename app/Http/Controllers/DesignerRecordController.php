<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Designer;
use App\Models\DesignerRecord;
use App\Models\MonthlyEvaluation;
use App\Models\WeeklyEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignerRecordController extends Controller
{
    private array $scoreFields = [
        'quality_score' => 15,
        'details_score' => 10,
        'execution_score' => 15,
        'speed_score' => 10,
        'brief_score' => 10,
        'production_score' => 15,
        'followup_score' => 10,
        'teamwork_score' => 5,
        'flexibility_score' => 10,
    ];

    public function index(Request $request): View
    {
        $query = DesignerRecord::with(['designer', 'monthlyEvaluations'])
            ->withCount('documents')
            ->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%")
                    ->orWhereHas('designer', fn ($designer) => $designer->where('name', 'like', "%{$search}%"));
            });
        }

        return view('records.index', [
            'records' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('records.form', [
            'record' => new DesignerRecord(['current_month' => 'month1', 'project_status' => 'new']),
            'designers' => $this->designers(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCreateRecord($request);
        $designer = Designer::findOrFail($data['designer_id']);

        $data['created_by'] = $request->user()->id;
        $data['employee_name'] = $data['employee_name'] ?: $designer->name;
        $data['job_title'] = $designer->job_title;
        $data['start_date'] = $data['start_date'] ?: optional($designer->start_date)->format('Y-m-d');
        $data['manager_name'] = $data['manager_name'] ?: $designer->direct_manager;
        $data['trial_period'] = $data['trial_period'] ?: $designer->trial_period;
        $data['current_salary'] = $designer->current_salary;
        $data['proposed_raise'] = $designer->proposed_raise;
        $data['project_status'] = $data['project_status'] ?? 'new';

        $record = DesignerRecord::create($data);
        $this->logChange($record, $request, 'created', 'تم إنشاء سجل تقييم جديد.', $data);

        return redirect()
            ->route('records.show', $record)
            ->with('status', 'تم إنشاء سجل التقييم. يمكنك الآن إضافة المتابعة الأسبوعية أو ملاحظات الأداء.');
    }

    public function show(Request $request, DesignerRecord $record): View
    {
        $this->ensureAccess($request, $record);
        $record->load([
            'designer',
            'weeklyEntries',
            'monthlyEvaluations',
            'activityLogs',
            'documents.uploader',
            'changes.user',
        ]);

        return view('records.show', ['record' => $record]);
    }

    public function edit(Request $request, DesignerRecord $record): View
    {
        $this->ensureAccess($request, $record);
        $record->load(['weeklyEntries', 'monthlyEvaluations', 'activityLogs']);

        return view('records.form', [
            'record' => $record,
            'designers' => $this->designers(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, DesignerRecord $record): RedirectResponse
    {
        $this->ensureAccess($request, $record);
        $data = $this->validatedRecord($request);

        $record->fill($data);
        $dirty = $record->getDirty();
        $record->save();
        $this->syncChildren($record, $request);
        $this->logChange(
            $record,
            $request,
            'updated',
            empty($dirty) ? 'تم حفظ الملف دون تغيير بياناته الأساسية.' : 'تم تعديل بيانات الملف الأساسية.',
            $dirty
        );

        return redirect()->route('records.show', $record)->with('status', 'تم تحديث سجل التقييم.');
    }

    public function destroy(Request $request, DesignerRecord $record): RedirectResponse
    {
        abort_unless($request->user()->canManageDesignerData(), 403);
        $record->delete();

        return redirect()->route('records.index')->with('status', 'تم حذف سجل التقييم.');
    }

    private function validatedCreateRecord(Request $request): array
    {
        return $request->validate([
            'designer_id' => ['required', 'exists:designers,id'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'trial_period' => ['required', 'string', 'max:255'],
            'current_month' => ['required', 'in:month1,month2,month3'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'project_status' => ['nullable', 'in:new,in_progress,waiting_customer,sent,approved,closed'],
            'customer_request' => ['nullable', 'string'],
            'designer_notes' => ['nullable', 'string'],
        ]);
    }

    private function validatedRecord(Request $request): array
    {
        return $request->validate([
            'designer_id' => ['required', 'exists:designers,id'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'trial_period' => ['required', 'string', 'max:255'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'proposed_raise' => ['nullable', 'string', 'max:255'],
            'current_month' => ['required', 'in:month1,month2,month3'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'project_name' => ['nullable', 'string', 'max:255'],
            'project_status' => ['required', 'in:new,in_progress,waiting_customer,sent,approved,closed'],
            'customer_request' => ['nullable', 'string'],
            'designer_notes' => ['nullable', 'string'],
            'manager_summary' => ['nullable', 'string'],
            'final_decision' => ['nullable', 'string', 'max:255'],
            'decision_date' => ['nullable', 'date'],
            'decision_reason' => ['nullable', 'string'],
            'next_plan' => ['nullable', 'string'],
        ]);
    }

    private function syncChildren(DesignerRecord $record, Request $request): void
    {
        $touchedWeekly = false;
        foreach ($request->input('weekly', []) as $row) {
            WeeklyEntry::updateOrCreate(
                ['designer_record_id' => $record->id, 'week_label' => $row['week_label']],
                [
                    'project' => $row['project'] ?? null,
                    'positive' => $row['positive'] ?? null,
                    'negative' => $row['negative'] ?? null,
                    'flexibility' => $row['flexibility'] ?? null,
                    'production_error' => ! empty($row['production_error']),
                    'manager_note' => $row['manager_note'] ?? null,
                ]
            );
            $touchedWeekly = true;
        }

        $touchedEvaluations = false;
        foreach ($request->input('evaluations', []) as $monthKey => $row) {
            $scores = [];
            foreach ($this->scoreFields as $field => $max) {
                $scores[$field] = min(max((int) ($row[$field] ?? 0), 0), $max);
            }

            MonthlyEvaluation::updateOrCreate(
                ['designer_record_id' => $record->id, 'month_key' => $monthKey],
                [
                    ...$scores,
                    'total_score' => array_sum($scores),
                    'notes' => $row['notes'] ?? [],
                    'manager_answers' => $row['manager_answers'] ?? [],
                ]
            );
            $touchedEvaluations = true;
        }

        $touchedNotes = false;
        foreach ($request->input('logs', []) as $row) {
            if (blank($row['note'] ?? null)) {
                continue;
            }

            ActivityLog::updateOrCreate(
                ['id' => $row['id'] ?? null, 'designer_record_id' => $record->id],
                [
                    'type' => $row['type'] ?? 'ملاحظة',
                    'project' => $row['project'] ?? null,
                    'note' => $row['note'],
                    'impact' => $row['impact'] ?? null,
                    'logged_at' => $row['logged_at'] ?? now()->toDateString(),
                ]
            );
            $touchedNotes = true;
        }

        $childrenSummary = array_filter([
            $touchedWeekly ? 'المتابعة الأسبوعية' : null,
            $touchedEvaluations ? 'التقييمات الشهرية' : null,
            $touchedNotes ? 'ملاحظات العمل' : null,
        ]);

        if ($childrenSummary && request()->routeIs('records.update')) {
            $this->logChange($record, request(), 'modules_updated', 'تم حفظ موديولات: '.implode('، ', $childrenSummary).'.');
        }
    }

    private function designers()
    {
        return Designer::orderBy('name')->get();
    }

    private function logChange(DesignerRecord $record, Request $request, string $action, string $summary, array $changes = []): void
    {
        $record->changes()->create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'summary' => $summary,
            'changes' => $changes,
        ]);
    }

    private function ensureAccess(Request $request, DesignerRecord $record): void
    {
        abort_unless($request->user(), 403);
    }
}
