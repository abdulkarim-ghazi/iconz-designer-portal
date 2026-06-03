@extends('layouts.app')

@php
    $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $criteria = [
        'quality_score' => ['جودة التصميم والإبداع', 15],
        'details_score' => ['دقة التفاصيل', 10],
        'execution_score' => ['قابلية التصميم للتنفيذ', 15],
        'speed_score' => ['سرعة الإنجاز والالتزام', 10],
        'brief_score' => ['فهم طلب الزبون', 10],
        'production_score' => ['ملفات الإنتاج والطباعة', 15],
        'followup_score' => ['المتابعة مع المعمل أو الموقع', 10],
        'teamwork_score' => ['التعاون مع الأقسام', 5],
        'flexibility_score' => ['المرونة والاستجابة', 10],
    ];
    $weeklyByLabel = $record->weeklyEntries->keyBy('week_label');
    $evalByMonth = $record->monthlyEvaluations->keyBy('month_key');
@endphp

@section('title', $mode === 'create' ? 'إضافة سجل' : 'تعديل سجل')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">إدخال بيانات المصمم</span>
        <h1>{{ $mode === 'create' ? 'إضافة سجل جديد' : 'تعديل السجل' }}</h1>
        <p class="muted">هذه البيانات تحفظ في قاعدة البيانات وتظهر للإدارة مباشرة.</p>
    </div>
</header>

<form method="POST" action="{{ $mode === 'create' ? route('records.store') : route('records.update', $record) }}" class="grid">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif

    <section class="panel">
        <h2>بيانات أساسية</h2>
        <div class="form-grid">
            <div class="field">
                <label>المصمم</label>
                <select name="designer_id" required>
                    @foreach($designers as $designer)
                        <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id ?: auth()->id()) == $designer->id)>{{ $designer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>اسم الموظفة الظاهر</label>
                <input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}">
            </div>
            <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title ?: 'مصمم / مصممة') }}" required></div>
            <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
            <div class="field"><label>المدير المباشر</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}"></div>
            <div class="field"><label>مدة التجربة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period ?: '3 أشهر') }}" required></div>
            <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
            <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}"></div>
            <div class="field"><label>الشهر الحالي</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="field"><label>حالة المشروع</label><select name="project_status">@foreach(['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'] as $key=>$label)<option value="{{ $key }}" @selected(old('project_status', $record->project_status) === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="field"><label>اسم الزبون</label><input name="customer_name" value="{{ old('customer_name', $record->customer_name) }}"></div>
            <div class="field"><label>اسم المشروع</label><input name="project_name" value="{{ old('project_name', $record->project_name) }}"></div>
            <div class="field full"><label>طلب الزبون</label><textarea name="customer_request">{{ old('customer_request', $record->customer_request) }}</textarea></div>
            <div class="field full"><label>ملاحظات المصمم</label><textarea name="designer_notes">{{ old('designer_notes', $record->designer_notes) }}</textarea></div>
            <div class="field full"><label>ملخص الإدارة</label><textarea name="manager_summary">{{ old('manager_summary', $record->manager_summary) }}</textarea></div>
        </div>
    </section>

    <section class="panel">
        <h2>Scoreboard أسبوعي</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>الأسبوع</th><th>المشروع</th><th>إيجابي</th><th>سلبي</th><th>مرونة</th><th>خطأ إنتاجي</th><th>ملاحظة الإدارة</th></tr></thead>
                <tbody>
                    @foreach($weeks as $i => $week)
                        @php($row = $weeklyByLabel->get($week))
                        <tr>
                            <td><input type="hidden" name="weekly[{{ $i }}][week_label]" value="{{ $week }}"><strong>{{ $week }}</strong></td>
                            <td><input name="weekly[{{ $i }}][project]" value="{{ $row?->project }}"></td>
                            <td><textarea name="weekly[{{ $i }}][positive]">{{ $row?->positive }}</textarea></td>
                            <td><textarea name="weekly[{{ $i }}][negative]">{{ $row?->negative }}</textarea></td>
                            <td><select name="weekly[{{ $i }}][flexibility]"><option></option><option @selected($row?->flexibility==='نعم')>نعم</option><option @selected($row?->flexibility==='جزئياً')>جزئياً</option><option @selected($row?->flexibility==='لا')>لا</option></select></td>
                            <td><input type="checkbox" name="weekly[{{ $i }}][production_error]" value="1" @checked($row?->production_error)></td>
                            <td><textarea name="weekly[{{ $i }}][manager_note]">{{ $row?->manager_note }}</textarea></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h2>التقييم الشهري</h2>
        @foreach($monthLabels as $monthKey => $monthLabel)
            @php($eval = $evalByMonth->get($monthKey))
            <h3>{{ $monthLabel }}</h3>
            <div class="table-wrap" style="margin-bottom:14px">
                <table>
                    <thead><tr><th>المحور</th><th>الوزن</th><th>النقطة</th><th>ملاحظة</th></tr></thead>
                    <tbody>
                        @foreach($criteria as $field => [$label, $max])
                            <tr>
                                <td>{{ $label }}</td>
                                <td>/{{ $max }}</td>
                                <td><input type="number" min="0" max="{{ $max }}" name="evaluations[{{ $monthKey }}][{{ $field }}]" value="{{ $eval?->{$field} ?? 0 }}"></td>
                                <td><input name="evaluations[{{ $monthKey }}][notes][{{ $field }}]" value="{{ $eval?->notes[$field] ?? '' }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="field full">
                <label>إجابة المدير / خلاصة {{ $monthLabel }}</label>
                <textarea name="evaluations[{{ $monthKey }}][manager_answers][summary]">{{ $eval?->manager_answers['summary'] ?? '' }}</textarea>
            </div>
        @endforeach
    </section>

    <section class="panel">
        <h2>السجلات والملاحظات</h2>
        @for($i = 0; $i < 3; $i++)
            @php($log = $record->activityLogs[$i] ?? null)
            <div class="form-grid" style="margin-bottom:12px">
                <input type="hidden" name="logs[{{ $i }}][id]" value="{{ $log?->id }}">
                <div class="field"><label>النوع</label><select name="logs[{{ $i }}][type]"><option>ملاحظة</option><option @selected($log?->type==='نقطة إيجابية')>نقطة إيجابية</option><option @selected($log?->type==='خطأ إنتاجي')>خطأ إنتاجي</option></select></div>
                <div class="field"><label>المشروع</label><input name="logs[{{ $i }}][project]" value="{{ $log?->project }}"></div>
                <div class="field"><label>التأثير</label><input name="logs[{{ $i }}][impact]" value="{{ $log?->impact }}"></div>
                <div class="field"><label>التاريخ</label><input type="date" name="logs[{{ $i }}][logged_at]" value="{{ optional($log?->logged_at)->format('Y-m-d') }}"></div>
                <div class="field full"><label>الملاحظة</label><textarea name="logs[{{ $i }}][note]">{{ $log?->note }}</textarea></div>
            </div>
        @endfor
    </section>

    <section class="panel">
        <h2>قرار الإدارة</h2>
        <div class="form-grid">
            <div class="field"><label>القرار النهائي</label><input name="final_decision" value="{{ old('final_decision', $record->final_decision ?: 'لم يتم اتخاذ القرار') }}"></div>
            <div class="field"><label>تاريخ القرار</label><input type="date" name="decision_date" value="{{ old('decision_date', optional($record->decision_date)->format('Y-m-d')) }}"></div>
            <div class="field full"><label>سبب القرار</label><textarea name="decision_reason">{{ old('decision_reason', $record->decision_reason) }}</textarea></div>
            <div class="field full"><label>الخطة القادمة</label><textarea name="next_plan">{{ old('next_plan', $record->next_plan) }}</textarea></div>
        </div>
    </section>

    <div class="actions">
        <button class="btn primary" type="submit">حفظ السجل</button>
        <a class="btn" href="{{ route('records.index') }}">رجوع</a>
    </div>
</form>
@endsection
