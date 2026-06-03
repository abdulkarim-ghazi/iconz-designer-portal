@extends('layouts.app')

@section('title', 'لوحة المتابعة')

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">iConz Designer Portal</span>
        <h1>لوحة المتابعة</h1>
        <p class="muted">مدخل سريع ومنطقي لعمل المصمم ومديره: متابعة الملفات المفتوحة، إضافة ملف جديد، ومراجعة آخر التحديثات.</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.create') }}">إضافة ملف متابعة جديد</a>
        <a class="btn" href="{{ route('records.index') }}">عرض كل الملفات</a>
    </div>
</header>

<div class="grid metrics">
    <div class="card metric"><span>كل ملفات المتابعة</span><strong>{{ $totalRecords }}</strong></div>
    <div class="card metric"><span>ملفات مفتوحة</span><strong>{{ $openRecords }}</strong></div>
    <div class="card metric"><span>مرسلة أو معتمدة</span><strong>{{ $sentRecords }}</strong></div>
    <div class="card metric"><span>موديولات العمل</span><strong>{{ auth()->user()->isAdmin() ? 5 : 4 }}</strong></div>
</div>

<div class="grid three" style="margin-top:16px">
    <a class="panel module-card" href="{{ route('records.index') }}">
        <span class="eyebrow">01</span>
        <h2>ملفات المتابعة</h2>
        <p class="muted">جدول لكل ملفات المصممين مع البحث والانتقال إلى الملف الكامل.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'designer-data') }}">
        <span class="eyebrow">02</span>
        <h2>بيانات المصمم</h2>
        <p class="muted">يدخلها رئيس القسم لمصمم معين، وتبقى مرتبطة بملفه الكامل.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'weekly-followup') }}">
        <span class="eyebrow">03</span>
        <h2>المتابعة الأسبوعية</h2>
        <p class="muted">يختار رئيس القسم المصمم ثم يسجل متابعة الأسابيع.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'performance-notes') }}">
        <span class="eyebrow">04</span>
        <h2>ملاحظات الأداء</h2>
        <p class="muted">ملاحظات أداء وعمل مرتبطة بمصمم وملف متابعة محدد.</p>
    </a>
    <a class="panel module-card" href="{{ route('records.create') }}">
        <span class="eyebrow">05</span>
        <h2>إدخال ملف جديد</h2>
        <p class="muted">إنشاء ملف متابعة يربط المصمم بالزبون والمشروع.</p>
    </a>
    <a class="panel module-card" href="{{ route('handbook') }}">
        <span class="eyebrow">06</span>
        <h2>دليل العمل</h2>
        <p class="muted">مرجع المصممة لفهم المطلوب ومعايير التقييم.</p>
    </a>
    @if(auth()->user()->isAdmin())
        <a class="panel module-card" href="{{ route('users.index') }}">
            <span class="eyebrow">04</span>
            <h2>إدارة المستخدمين</h2>
            <p class="muted">إضافة مصممين ومدراء وتحديد الصلاحيات.</p>
        </a>
    @endif
    <div class="panel module-card">
        <span class="eyebrow">داخل كل ملف</span>
        <h2>وثائق وتقييم وتغييرات</h2>
        <p class="muted">كل ملف يحتوي وثائقه، التقييمات، الملاحظات، وسجل التغييرات الخاص به.</p>
    </div>
</div>

<section class="panel" style="margin-top:16px">
    <h2>آخر ملفات المتابعة</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>المصمم</th><th>الزبون</th><th>المشروع</th><th>الحالة</th><th>وثائق</th><th>آخر تحديث</th></tr></thead>
            <tbody>
                @forelse($recentRecords as $record)
                    <tr onclick="window.location='{{ route('records.show', $record) }}'" style="cursor:pointer">
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->email }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ $record->documents_count }}</td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">لا توجد ملفات متابعة بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
