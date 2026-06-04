@extends('layouts.app')

@section('title', 'مصمم - '.$designer->name)

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $statusLabels = ['active' => 'نشط', 'on_hold' => 'مؤجل', 'completed' => 'مكتمل', 'inactive' => 'غير نشط'];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">بيانات مصمم</span>
        <h1>{{ $designer->name }}</h1>
        <p class="muted">{{ $designer->job_title }} · {{ $statusLabels[$designer->status] }}</p>
    </div>
    <div class="actions">
        @if($canManage)
            <a class="btn primary" href="{{ route('designers.edit', $designer) }}">تعديل بيانات المصمم</a>
            <a class="btn" href="{{ route('records.create') }}">إنشاء سجل تقييم</a>
        @endif
        <a class="btn" href="{{ route('designers.index') }}">كل المصممين</a>
    </div>
</header>

<div class="grid metrics">
    <div class="card metric"><span>مرحلة التقييم</span><strong>{{ $monthLabels[$designer->current_month] }}</strong></div>
    <div class="card metric"><span>سجلات التقييم</span><strong>{{ $designer->records_count }}</strong></div>
    <div class="card metric"><span>مدة المتابعة</span><strong>{{ $designer->trial_period }}</strong></div>
    <div class="card metric"><span>الحالة</span><strong>{{ $statusLabels[$designer->status] }}</strong></div>
</div>

<section class="panel" style="margin-top:16px">
    <h2>البيانات الأساسية</h2>
    <div class="grid two">
        <div>
            <p><strong>البريد:</strong> <span class="muted">{{ $designer->email ?: '-' }}</span></p>
            <p><strong>الهاتف:</strong> <span class="muted">{{ $designer->phone ?: '-' }}</span></p>
            <p><strong>تاريخ المباشرة:</strong> <span class="muted">{{ optional($designer->start_date)->format('Y-m-d') ?: '-' }}</span></p>
        </div>
        <div>
            <p><strong>المدير المباشر:</strong> <span class="muted">{{ $designer->direct_manager ?: '-' }}</span></p>
            <p><strong>الراتب الحالي:</strong> <span class="muted">{{ $designer->current_salary ?: '-' }}</span></p>
            <p><strong>الزيادة المقترحة:</strong> <span class="muted">{{ $designer->proposed_raise ?: '-' }}</span></p>
        </div>
    </div>
    <p><strong>ملاحظات داخلية</strong></p>
    <p class="muted">{{ $designer->notes ?: 'لا توجد ملاحظات.' }}</p>
</section>

<section class="panel" style="margin-top:16px">
    <h2>سجلات التقييم المرتبطة</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>الزبون</th><th>المشروع</th><th>مرحلة التقييم</th><th>متوسط التقييم</th><th>آخر تحديث</th><th>فتح</th></tr></thead>
            <tbody>
                @forelse($designer->records as $record)
                    <tr>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td>{{ $monthLabels[$record->current_month] }}</td>
                        <td><span class="badge green">{{ $record->totalScore() }}%</span></td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                        <td><a class="btn" href="{{ route('records.show', $record) }}">السجل الكامل</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">لا توجد ملفات متابعة لهذا المصمم بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
