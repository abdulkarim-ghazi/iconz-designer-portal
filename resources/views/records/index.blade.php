@extends('layouts.app')

@section('title', 'ملفات المتابعة')

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">موديول ملفات المتابعة</span>
        <h1>كل ملفات المتابعة</h1>
        <p class="muted">هذا الجدول هو نقطة الإدارة الرئيسية: كل صف يمثل ملف متابعة مستقل لزبون أو مشروع، ويفتح صفحة كاملة عند اختياره.</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.create') }}">إضافة ملف جديد</a>
        <a class="btn" href="{{ route('dashboard') }}">لوحة المتابعة</a>
    </div>
</header>

<div class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث باسم المصمم أو الزبون أو المشروع</label>
            <input name="search" value="{{ request('search') }}" placeholder="مثال: اسم الزبون، المشروع، أو المصمم">
        </div>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>المصمم</th>
                    <th>الزبون</th>
                    <th>المشروع</th>
                    <th>حالة الملف</th>
                    <th>مرحلة التقييم</th>
                    <th>متوسط التقييم</th>
                    <th>الوثائق</th>
                    <th>آخر تحديث</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr onclick="window.location='{{ route('records.show', $record) }}'" style="cursor:pointer">
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->email }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ $monthLabels[$record->current_month] }}</td>
                        <td><span class="badge green">{{ $record->totalScore() }}%</span></td>
                        <td>{{ $record->documents_count }}</td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا توجد ملفات متابعة بعد. ابدأ من زر إضافة ملف جديد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $records->links() }}</div>
</div>
@endsection
