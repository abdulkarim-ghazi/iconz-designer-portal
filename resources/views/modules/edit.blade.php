@extends('layouts.app')

@section('title', $meta['title'].' - '.$record->employee_name)

@php
    $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $weeklyByLabel = $record->weeklyEntries->keyBy('week_label');
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $meta['owner'] }}</span>
        <h1>{{ $meta['title'] }}</h1>
        <p class="muted">{{ $record->employee_name }} · {{ $record->customer_name ?: 'بدون زبون' }} · {{ $record->project_name ?: 'بدون مشروع' }}</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('modules.index', $module) }}">رجوع للموديول</a>
        <a class="btn" href="{{ route('records.show', $record) }}">الملف الكامل</a>
    </div>
</header>

<form method="POST" action="{{ route('modules.update', [$module, $record]) }}" class="grid" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @if($module === 'designer-data')
        <section class="panel">
            <h2>بيانات المصمم</h2>
            <p class="muted">هذا القسم مستقل ويضعه رئيس القسم. أي تغيير هنا يظهر داخل الملف الكامل وسجل التغييرات.</p>
            <div class="form-grid">
                <div class="field">
                    <label>المصمم</label>
                    <select name="designer_id" required>
                        @foreach($designers as $designer)
                            <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id) == $designer->id)>{{ $designer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>اسم الموظفة الظاهر</label><input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}"></div>
                <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title) }}" required></div>
                <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
                <div class="field"><label>المدير المباشر</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}"></div>
                <div class="field"><label>مدة التجربة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period) }}" required></div>
                <div class="field"><label>مرحلة التقييم الحالية</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
                <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}"></div>
            </div>
        </section>
    @endif

    @if($module === 'weekly-followup')
        <section class="panel">
            <h2>متابعة المصمم الأسبوعية</h2>
            <p class="muted">يضعها رئيس القسم لمصمم معين، وترتبط بنفس ملف المتابعة والزبون/المشروع.</p>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>الأسبوع</th><th>المشروع</th><th>نقطة إيجابية</th><th>نقطة سلبية</th><th>مرونة</th><th>خطأ إنتاجي</th><th>ملاحظة رئيس القسم</th></tr></thead>
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
    @endif

    @if($module === 'performance-notes')
        <section class="panel">
            <h2>ملاحظات الأداء والعمل</h2>
            <p class="muted">ملاحظات لمصمم معين. يمكن تسجيل نقطة إيجابية، خطأ إنتاجي، أو ملاحظة عامة مرتبطة بالمشروع.</p>
            @for($i = 0; $i < 5; $i++)
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
    @endif

    @if(in_array($module, ['weekly-followup', 'performance-notes'], true))
        <section class="panel">
            <h2>ملفات أو صور داعمة</h2>
            <p class="muted">ارفع صوراً، PDF، أو ملفات مرتبطة بهذه المتابعة أو الملاحظة. ستظهر داخل الملف الكامل للمصمم.</p>
            <div class="form-grid">
                <div class="field"><label>عنوان الملفات</label><input name="support_title" placeholder="مثال: صورة خطأ، ملف موافقة، لقطة متابعة"></div>
                <div class="field"><label>اختيار الملفات</label><input type="file" name="support_files[]" multiple></div>
            </div>
        </section>
    @endif

    <div class="actions">
        <button class="btn primary" type="submit">حفظ {{ $meta['title'] }}</button>
        <a class="btn" href="{{ route('modules.index', $module) }}">إلغاء</a>
    </div>
</form>
@endsection
