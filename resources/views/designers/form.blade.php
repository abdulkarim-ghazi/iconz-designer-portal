@extends('layouts.app')

@section('title', $mode === 'create' ? 'إضافة موظفة' : 'تعديل بطاقة الموظفة')

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $statusLabels = ['active' => 'نشط', 'on_hold' => 'مؤجل', 'completed' => 'مكتمل', 'inactive' => 'غير نشط'];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">بطاقة الموظفة</span>
        <h1>{{ $mode === 'create' ? 'إضافة موظفة' : 'تعديل بطاقة الموظفة' }}</h1>
        <p class="muted">هذه بطاقة الموظفة التي تتم متابعتها. ليست حساب دخول للنظام.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('designers.index') }}">كل البطاقات</a>
    </div>
</header>

@if($errors->any())
    <div class="alert">راجع بيانات البطاقة قبل الحفظ.</div>
@endif

<form method="POST" action="{{ $mode === 'create' ? route('designers.store') : route('designers.update', $designer) }}" class="panel">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label>اسم الموظفة</label><input name="name" value="{{ old('name', $designer->name) }}" required></div>
        <div class="field"><label>البريد الإلكتروني</label><input type="email" name="email" value="{{ old('email', $designer->email) }}" placeholder="اختياري"></div>
        <div class="field"><label>الهاتف</label><input name="phone" value="{{ old('phone', $designer->phone) }}" placeholder="اختياري"></div>
        <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $designer->job_title) }}" required></div>
        <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($designer->start_date)->format('Y-m-d')) }}"></div>
        <div class="field"><label>المدير المباشر</label><input name="direct_manager" value="{{ old('direct_manager', $designer->direct_manager) }}" placeholder="مدير التصميم"></div>
        <div class="field"><label>خطة المتابعة</label><input name="trial_period" value="{{ old('trial_period', $designer->trial_period) }}" required></div>
        <div class="field"><label>الشهر الحالي</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $designer->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
        <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $designer->current_salary) }}"></div>
        <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $designer->proposed_raise) }}"></div>
        <div class="field"><label>الحالة</label><select name="status">@foreach($statusLabels as $key => $label)<option value="{{ $key }}" @selected(old('status', $designer->status) === $key)>{{ $label }}</option>@endforeach</select></div>
        <div class="field full"><label>ملاحظات داخلية</label><textarea name="notes">{{ old('notes', $designer->notes) }}</textarea></div>
    </div>
    <div class="actions" style="margin-top:14px">
        <button class="btn primary" type="submit">حفظ البطاقة</button>
        <a class="btn" href="{{ route('designers.index') }}">إلغاء</a>
    </div>
</form>
@endsection
