<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DesignerRecord;
use App\Models\User;
use App\Models\WeeklyEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecordModuleController extends Controller
{
    private array $modules = [
        'designer-data' => [
            'title' => 'بيانات المصمم',
            'description' => 'يدخلها مدير التصميم لتعريف المصمم ومرحلة المتابعة.',
            'owner' => 'مدير التصميم',
        ],
        'weekly-followup' => [
            'title' => 'متابعة المصمم الأسبوعية',
            'description' => 'يسجلها مدير التصميم لمصمم معين حسب الأسابيع والمشاريع.',
            'owner' => 'مدير التصميم',
        ],
        'performance-notes' => [
            'title' => 'ملاحظات الأداء والعمل',
            'description' => 'ملاحظات مرتبطة بمصمم معين: أداء، أخطاء، نقاط إيجابية، أو مواقف تحتاج متابعة.',
            'owner' => 'مدير التصميم',
        ],
    ];

    public function index(Request $request, string $module): View
    {
        $meta = $this->moduleMeta($module);
        $query = DesignerRecord::with('designer')->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        return view('modules.index', [
            'module' => $module,
            'meta' => $meta,
            'records' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function edit(Request $request, string $module, DesignerRecord $record): View
    {
        $this->ensureAccess($request, $record);
        $meta = $this->moduleMeta($module);
        $record->load(['weeklyEntries', 'activityLogs']);

        return view('modules.edit', [
            'module' => $module,
            'meta' => $meta,
            'record' => $record,
            'designers' => $this->designers(),
        ]);
    }

    public function update(Request $request, string $module, DesignerRecord $record): RedirectResponse
    {
        abort_unless($request->user()->canManageDesignerData(), 403);
        $this->moduleMeta($module);

        match ($module) {
            'designer-data' => $this->updateDesignerData($request, $record),
            'weekly-followup' => $this->updateWeeklyFollowup($request, $record),
            'performance-notes' => $this->updatePerformanceNotes($request, $record),
            default => abort(404),
        };

        $this->storeSupportFiles($request, $record, $module);

        return redirect()
            ->route('modules.edit', [$module, $record])
            ->with('status', 'تم حفظ الموديول وربطه بملف المتابعة.');
    }

    private function updateDesignerData(Request $request, DesignerRecord $record): void
    {
        $data = $request->validate([
            'designer_id' => ['required', 'exists:users,id'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'trial_period' => ['required', 'string', 'max:255'],
            'current_month' => ['required', 'in:month1,month2,month3'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'proposed_raise' => ['nullable', 'string', 'max:255'],
        ]);

        $data['employee_name'] = $data['employee_name'] ?: User::find($data['designer_id'])->name;
        $record->fill($data);
        $dirty = $record->getDirty();
        $record->save();
        $this->logChange($request, $record, 'designer_data_updated', 'تم تحديث بيانات المصمم.', $dirty);
    }

    private function updateWeeklyFollowup(Request $request, DesignerRecord $record): void
    {
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
        }

        $this->logChange($request, $record, 'weekly_followup_updated', 'تم تحديث المتابعة الأسبوعية.');
    }

    private function updatePerformanceNotes(Request $request, DesignerRecord $record): void
    {
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
        }

        $this->logChange($request, $record, 'performance_notes_updated', 'تم تحديث ملاحظات الأداء والعمل.');
    }

    private function moduleMeta(string $module): array
    {
        abort_unless(isset($this->modules[$module]), 404);

        return $this->modules[$module];
    }

    private function storeSupportFiles(Request $request, DesignerRecord $record, string $module): void
    {
        $request->validate([
            'support_title' => ['nullable', 'string', 'max:255'],
            'support_files' => ['nullable', 'array'],
            'support_files.*' => ['file', 'max:20480', 'mimes:jpg,jpeg,png,webp,pdf,txt,doc,docx,xls,xlsx'],
        ]);

        if (! $request->hasFile('support_files')) {
            return;
        }

        foreach ($request->file('support_files', []) as $file) {
            $path = $file->store("records/{$record->id}", 'public');
            $record->documents()->create([
                'uploaded_by' => $request->user()->id,
                'title' => $request->input('support_title') ?: $this->modules[$module]['title'],
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        $this->logChange($request, $record, 'support_files_uploaded', 'تم رفع ملفات داعمة من موديول '.$this->modules[$module]['title'].'.', [
            'module' => $module,
            'count' => count($request->file('support_files', [])),
        ]);
    }

    private function designers()
    {
        return User::where('role', 'designer')->orderBy('name')->get();
    }

    private function logChange(Request $request, DesignerRecord $record, string $action, string $summary, array $changes = []): void
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
