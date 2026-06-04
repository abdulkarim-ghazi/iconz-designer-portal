@extends('layouts.app')

@php
    $isCreate = $mode === 'create';
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('title', $isCreate ? 'إضافة نشاط للموظفة' : 'تعديل نشاط الموظفة')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">بطاقة الموظفة</span>
        <h1>{{ $isCreate ? 'إضافة نشاط للموظفة' : 'تعديل نشاط الموظفة' }}</h1>
        <p class="muted">استخدم هذه الصفحة فقط إذا كان النشاط مرتبطاً بزبون أو مشروع محدد. بطاقة الموظفة نفسها تدار من قسم بطاقة الموظفة.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('designers.index') }}">بطاقات الموظفات</a>
    </div>
</header>

@if($designers->isEmpty())
    <div class="alert">
        لا توجد موظفات بعد. أضف موظفة أولاً من بطاقة الموظفة.
        <a class="btn primary" href="{{ route('designers.create') }}" style="margin-right:10px">إضافة موظفة</a>
    </div>
@endif

<form method="POST" action="{{ $isCreate ? route('records.store') : route('records.update', $record) }}" class="grid">
    @csrf
    @unless($isCreate)
        @method('PUT')
    @endunless

    <fieldset @disabled(! $canManage || $designers->isEmpty()) class="grid" style="border:0;padding:0;margin:0">
        <section class="panel">
            <h2>الموظفة والسياق</h2>
            <p class="muted">اختر الموظفة، ثم أضف الزبون أو المشروع فقط إذا كان هذا النشاط مرتبطاً بسياق محدد.</p>
            <div class="form-grid">
                <div class="field">
                    <label>الموظفة</label>
                    <select name="designer_id" required>
                        <option value="">اختر الموظفة</option>
                        @foreach($designers as $designer)
                            <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id) == $designer->id)>{{ $designer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>اسم ظاهر داخل البطاقة</label><input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}" placeholder="اختياري، اتركه فارغاً لاستخدام اسم الموظفة"></div>
                <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title) }}" required></div>
                <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
                <div class="field"><label>المسؤول عن المتابعة</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}" placeholder="مدير التصميم"></div>
                <div class="field"><label>مدة المتابعة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period ?: '3 أشهر') }}" required></div>
                <div class="field"><label>الشهر الحالي</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="field"><label>حالة النشاط</label><select name="project_status">@foreach($statusLabels as $key => $label)<option value="{{ $key }}" @selected(old('project_status', $record->project_status) === $key)>{{ $label }}</option>@endforeach</select></div>
            </div>
        </section>

        <section class="panel">
            <h2>الزبون أو المشروع</h2>
            <div class="form-grid">
                <div class="field"><label>اسم الزبون</label><input name="customer_name" value="{{ old('customer_name', $record->customer_name) }}"></div>
                <div class="field"><label>اسم المشروع</label><input name="project_name" value="{{ old('project_name', $record->project_name) }}"></div>
                <div class="field full"><label>طلب الزبون / سياق العمل</label><textarea name="customer_request">{{ old('customer_request', $record->customer_request) }}</textarea></div>
                <div class="field full"><label>ملاحظات أولية</label><textarea name="designer_notes">{{ old('designer_notes', $record->designer_notes) }}</textarea></div>
            </div>
        </section>
    </fieldset>

    <div class="actions">
        @if($canManage)
            <button class="btn primary" type="submit" @disabled($designers->isEmpty())>{{ $isCreate ? 'حفظ النشاط' : 'حفظ التعديل' }}</button>
        @endif
        <a class="btn" href="{{ route('designers.index') }}">إلغاء</a>
    </div>
</form>
@endsection
