<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DesignerRecord;
use App\Models\MonthlyEvaluation;
use App\Models\User;
use App\Models\WeeklyEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        if (! $request->user()->isAdmin()) {
            $query->where('designer_id', $request->user()->id);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%");
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
            'designers' => $this->designers($request),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCreateRecord($request);
        $designerId = $this->resolveDesignerForCreate($request);

        $data['designer_id'] = $designerId;
        $data['created_by'] = $request->user()->id;
        $data['employee_name'] = $data['employee_name'] ?: User::find($designerId)->name;
        $data['project_status'] = 'new';

        unset($data['new_designer_name'], $data['new_designer_email']);

        $record = DesignerRecord::create($data);
        $this->logChange($record, $request, 'created', 'تم إنشاء سجل مصمم جديد.', $data);

        return redirect()
            ->route('records.show', $record)
            ->with('status', 'تم إنشاء سجل المصمم. أضف المتابعة الأسبوعية أو ملاحظات الأداء من القوائم المستقلة.');
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
            'designers' => $this->designers($request),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, DesignerRecord $record): RedirectResponse
    {
        $this->ensureAccess($request, $record);
        $data = $this->validatedRecord($request);

        if (! $request->user()->isAdmin()) {
            unset(
                $data['designer_id'],
                $data['current_salary'],
                $data['proposed_raise'],
                $data['manager_summary'],
                $data['final_decision'],
                $data['decision_date'],
                $data['decision_reason'],
                $data['next_plan']
            );
        }

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

        return redirect()->route('records.show', $record)->with('status', 'تم تحديث ملف المتابعة.');
    }

    public function destroy(Request $request, DesignerRecord $record): RedirectResponse
    {
        $this->ensureAccess($request, $record, adminOnly: true);
        $record->delete();

        return redirect()->route('records.index')->with('status', 'تم حذف ملف المتابعة.');
    }

    private function validatedCreateRecord(Request $request): array
    {
        return $request->validate([
            'designer_id' => ['required', 'string'],
            'new_designer_name' => ['nullable', 'string', 'max:255'],
            'new_designer_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'trial_period' => ['required', 'string', 'max:255'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'proposed_raise' => ['nullable', 'string', 'max:255'],
            'current_month' => ['required', 'in:month1,month2,month3'],
        ]);
    }

    private function validatedRecord(Request $request): array
    {
        return $request->validate([
            'designer_id' => ['required', 'exists:users,id'],
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

    private function resolveDesignerForCreate(Request $request): int
    {
        if ($request->input('designer_id') !== 'new') {
            $request->validate(['designer_id' => ['required', 'exists:users,id']]);

            return (int) $request->input('designer_id');
        }

        $request->validate(['new_designer_name' => ['required', 'string', 'max:255']]);

        $name = $request->string('new_designer_name')->toString();
        $email = $request->input('new_designer_email')
            ?: Str::slug($name).'-'.Str::lower(Str::random(6)).'@iconz.local';

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(32)),
            'role' => 'designer',
            'is_active' => false,
        ])->id;
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

    private function designers(Request $request)
    {
        return User::where('role', 'designer')->orderBy('name')->get();
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

    private function ensureAccess(Request $request, DesignerRecord $record, bool $adminOnly = false): void
    {
        if ($adminOnly) {
            abort_unless($request->user()->isAdmin(), 403);
            return;
        }

        abort_unless($request->user()->isAdmin() || $record->designer_id === $request->user()->id, 403);
    }
}
