@extends('layouts.app')

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $isCreate = $mode === 'create';
@endphp

@section('title', $isCreate ? 'إنشاء سجل مصمم' : 'تعديل ملف متابعة')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $isCreate ? 'مدير التصميم' : 'ملف متابعة' }}</span>
        <h1>{{ $isCreate ? 'إنشاء سجل مصمم جديد' : 'تعديل ملف المتابعة' }}</h1>
        <p class="muted">
            @if($isCreate)
                هذه الشاشة لإنشاء سجل مصمم فقط. بعد إنشاء السجل، تتم المتابعة الأسبوعية وملاحظات الأداء من قوائمها المستقلة.
            @else
                تعديل المعلومات العامة للملف. المتابعة والملاحظات لها موديولات مستقلة في القائمة.
            @endif
        </p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('records.index') }}">كل الملفات</a>
    </div>
</header>

@if($errors->any())
    <div class="alert">يوجد حقول تحتاج مراجعة قبل الحفظ.</div>
@endif

<form method="POST" action="{{ $isCreate ? route('records.store') : route('records.update', $record) }}" class="grid">
    @csrf
    @if(! $isCreate) @method('PUT') @endif

    <section class="panel">
        <span class="eyebrow">سجل المصمم</span>
        <h2>بيانات المصمم الأساسية</h2>
        <p class="muted">يضعها مدير التصميم عند دخول مصمم جديد أو بدء فترة متابعة جديدة.</p>

        <div class="form-grid">
            @if($isCreate)
                <div class="field">
                    <label>المصمم</label>
                    <select name="designer_id" id="designerChoice" required>
                        <option value="new" @selected(old('designer_id') === 'new')>مصمم جديد</option>
                        @foreach($designers as $designer)
                            <option value="{{ $designer->id }}" @selected(old('designer_id') == $designer->id)>{{ $designer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>اسم المصمم الجديد</label><input name="new_designer_name" value="{{ old('new_designer_name') }}" placeholder="يستخدم عند اختيار مصمم جديد"></div>
                <div class="field"><label>بريد المصمم الجديد</label><input type="email" name="new_designer_email" value="{{ old('new_designer_email') }}" placeholder="اختياري"></div>
            @else
                <div class="field">
                    <label>المصمم</label>
                    <select name="designer_id" required>
                        @foreach($designers as $designer)
                            <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id) == $designer->id)>{{ $designer->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="field"><label>اسم الموظف الظاهر في السجل</label><input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}" placeholder="اتركه فارغاً لاستخدام اسم المصمم"></div>
            <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title ?: 'مصمم / مصممة') }}" required></div>
            <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
            <div class="field"><label>المدير المباشر</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}" placeholder="مدير التصميم"></div>
            <div class="field"><label>مدة التجربة / المتابعة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period ?: '3 أشهر') }}" required></div>
            <div class="field"><label>مرحلة التقييم الحالية</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
            <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}"></div>
        </div>
    </section>

    @if(! $isCreate)
        <section class="panel">
            <span class="eyebrow">معلومات عامة</span>
            <h2>ربط اختياري بزبون أو مشروع</h2>
            <p class="muted">هذا الربط اختياري إذا كانت المتابعة مرتبطة بمشروع محدد.</p>
            <div class="form-grid">
                <div class="field"><label>اسم الزبون</label><input name="customer_name" value="{{ old('customer_name', $record->customer_name) }}"></div>
                <div class="field"><label>اسم المشروع</label><input name="project_name" value="{{ old('project_name', $record->project_name) }}"></div>
                <div class="field"><label>حالة الملف</label><select name="project_status">@foreach(['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'] as $key=>$label)<option value="{{ $key }}" @selected(old('project_status', $record->project_status) === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="field full"><label>طلب الزبون</label><textarea name="customer_request">{{ old('customer_request', $record->customer_request) }}</textarea></div>
                <div class="field full"><label>ملاحظات المصمم</label><textarea name="designer_notes">{{ old('designer_notes', $record->designer_notes) }}</textarea></div>
                <div class="field full"><label>ملخص الإدارة</label><textarea name="manager_summary">{{ old('manager_summary', $record->manager_summary) }}</textarea></div>
                <div class="field"><label>القرار النهائي</label><input name="final_decision" value="{{ old('final_decision', $record->final_decision ?: 'لم يتم اتخاذ القرار') }}"></div>
                <div class="field"><label>تاريخ القرار</label><input type="date" name="decision_date" value="{{ old('decision_date', optional($record->decision_date)->format('Y-m-d')) }}"></div>
                <div class="field full"><label>سبب القرار</label><textarea name="decision_reason">{{ old('decision_reason', $record->decision_reason) }}</textarea></div>
                <div class="field full"><label>الخطة القادمة</label><textarea name="next_plan">{{ old('next_plan', $record->next_plan) }}</textarea></div>
            </div>
        </section>
    @else
        <input type="hidden" name="project_status" value="new">
    @endif

    <div class="actions">
        <button class="btn primary" type="submit">{{ $isCreate ? 'إنشاء سجل المصمم' : 'حفظ التعديل' }}</button>
        <a class="btn" href="{{ route('records.index') }}">إلغاء</a>
    </div>
</form>
@endsection
