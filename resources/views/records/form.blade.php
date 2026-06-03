@extends('layouts.app')

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $isCreate = $mode === 'create';
@endphp

@section('title', $isCreate ? 'إنشاء ملف متابعة' : 'تعديل ملف متابعة')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">ملف متابعة</span>
        <h1>{{ $isCreate ? 'إنشاء ملف متابعة' : 'تعديل ملف متابعة' }}</h1>
        <p class="muted">
            {{ $isCreate
                ? 'اختر مصمماً من قسم المصممين ثم أنشئ له ملف متابعة. المصمم هنا ليس مستخدم نظام.'
                : 'تعديل بيانات ملف المتابعة العامة. المتابعة الأسبوعية وملاحظات الأداء لها موديولات مستقلة.' }}
        </p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('records.index') }}">كل الملفات</a>
        <a class="btn" href="{{ route('designers.index') }}">قسم المصممين</a>
        <a class="btn" href="{{ route('modules.index', 'designer-data') }}">بيانات المصمم</a>
    </div>
</header>

@if($errors->any())
    <div class="alert">يوجد حقول تحتاج مراجعة قبل الحفظ.</div>
@endif

@if($designers->isEmpty())
    <div class="alert">
        لا يوجد مصممون بعد. أضف مصمماً من قسم المصممين أولاً، ثم ارجع لإنشاء ملف المتابعة.
        <a class="btn primary" href="{{ route('designers.create') }}" style="margin-right:10px">إضافة مصمم</a>
    </div>
@endif

<form method="POST" action="{{ $isCreate ? route('records.store') : route('records.update', $record) }}" class="grid">
    @csrf
    @if(! $isCreate) @method('PUT') @endif

    <section class="panel">
        <span class="eyebrow">الربط الأساسي</span>
        <h2>المصمم وملف المتابعة</h2>
        <p class="muted">بيانات المصمم الأساسية تدار من قسم المصممين. هنا تختار المصمم وتحدد سياق ملف المتابعة فقط.</p>
        <div class="form-grid">
            <div class="field">
                <label>المصمم</label>
                <select name="designer_id" required>
                    <option value="">اختر المصمم</option>
                    @foreach($designers as $designer)
                        <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id) == $designer->id)>{{ $designer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>اسم ظاهر داخل ملف المتابعة</label><input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}" placeholder="اختياري، اتركه فارغاً لاستخدام اسم المصمم"></div>
            @if(! $isCreate)
                <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title ?: 'مصمم / مصممة') }}" required></div>
            @endif
            <div class="field"><label>تاريخ بداية المتابعة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
            <div class="field"><label>المسؤول عن المتابعة</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}" placeholder="مدير التصميم"></div>
            <div class="field"><label>مدة المتابعة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period ?: '3 أشهر') }}" required></div>
            <div class="field"><label>مرحلة التقييم الحالية</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
            @if(! $isCreate)
                <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
                <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}"></div>
            @endif
        </div>
    </section>

    <section class="panel">
        <span class="eyebrow">اختياري</span>
        <h2>ربط الملف بزبون أو مشروع</h2>
        <p class="muted">استخدم هذه الحقول إذا كانت فترة المتابعة مرتبطة بزبون أو مشروع محدد.</p>
        <div class="form-grid">
            <div class="field"><label>اسم الزبون</label><input name="customer_name" value="{{ old('customer_name', $record->customer_name) }}"></div>
            <div class="field"><label>اسم المشروع</label><input name="project_name" value="{{ old('project_name', $record->project_name) }}"></div>
            <div class="field"><label>حالة الملف</label><select name="project_status">@foreach($statusLabels as $key=>$label)<option value="{{ $key }}" @selected(old('project_status', $record->project_status ?: 'new') === $key)>{{ $label }}</option>@endforeach</select></div>
            <div class="field full"><label>طلب الزبون / سياق المتابعة</label><textarea name="customer_request">{{ old('customer_request', $record->customer_request) }}</textarea></div>
            <div class="field full"><label>ملاحظات أولية</label><textarea name="designer_notes">{{ old('designer_notes', $record->designer_notes) }}</textarea></div>
        </div>
    </section>

    @if(! $isCreate)
        <section class="panel">
            <span class="eyebrow">إدارة</span>
            <h2>قرار وخلاصة الإدارة</h2>
            <div class="form-grid">
                <div class="field full"><label>ملخص الإدارة</label><textarea name="manager_summary">{{ old('manager_summary', $record->manager_summary) }}</textarea></div>
                <div class="field"><label>القرار النهائي</label><input name="final_decision" value="{{ old('final_decision', $record->final_decision ?: 'لم يتم اتخاذ القرار') }}"></div>
                <div class="field"><label>تاريخ القرار</label><input type="date" name="decision_date" value="{{ old('decision_date', optional($record->decision_date)->format('Y-m-d')) }}"></div>
                <div class="field full"><label>سبب القرار</label><textarea name="decision_reason">{{ old('decision_reason', $record->decision_reason) }}</textarea></div>
                <div class="field full"><label>الخطة القادمة</label><textarea name="next_plan">{{ old('next_plan', $record->next_plan) }}</textarea></div>
            </div>
        </section>
    @endif

    <div class="actions">
        <button class="btn primary" type="submit" @disabled($designers->isEmpty())>{{ $isCreate ? 'إنشاء ملف متابعة' : 'حفظ التعديل' }}</button>
        <a class="btn" href="{{ route('records.index') }}">إلغاء</a>
    </div>
</form>
@endsection
