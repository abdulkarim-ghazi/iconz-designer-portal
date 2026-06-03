@extends('layouts.app')

@section('title', 'لوحة المتابعة')

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">iConz Designer Portal</span>
        <h1>لوحة متابعة المصممين</h1>
        <p class="muted">النظام مخصص لمدير التصميم والمدير العام: إنشاء سجل مصمم، ثم إضافة متابعة أسبوعية وملاحظات أداء دورية، ثم مراجعة القرار.</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.create') }}">إنشاء سجل مصمم</a>
        <a class="btn" href="{{ route('records.index') }}">كل ملفات المتابعة</a>
    </div>
</header>

<div class="grid metrics">
    <div class="card metric"><span>سجلات المصممين</span><strong>{{ $totalRecords }}</strong></div>
    <div class="card metric"><span>ملفات قيد المتابعة</span><strong>{{ $openRecords }}</strong></div>
    <div class="card metric"><span>ملفات مرسلة أو معتمدة</span><strong>{{ $sentRecords }}</strong></div>
    <div class="card metric"><span>موديولات العمل</span><strong>5</strong></div>
</div>

<div class="grid three" style="margin-top:16px">
    <a class="panel module-card" href="{{ route('records.create') }}">
        <span class="eyebrow">01</span>
        <h2>إنشاء سجل مصمم</h2>
        <p class="muted">يستخدمه مدير التصميم عند دخول مصمم جديد أو بدء فترة متابعة جديدة.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'weekly-followup') }}">
        <span class="eyebrow">02</span>
        <h2>المتابعة الأسبوعية</h2>
        <p class="muted">يختار مدير التصميم مصمماً معيناً ثم يضيف متابعة الأسبوع.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'performance-notes') }}">
        <span class="eyebrow">03</span>
        <h2>ملاحظات الأداء والعمل</h2>
        <p class="muted">ملاحظات دورية لمصمم معين: نقطة إيجابية، خطأ، أو ملاحظة تطوير.</p>
    </a>
    <a class="panel module-card" href="{{ route('modules.index', 'designer-data') }}">
        <span class="eyebrow">04</span>
        <h2>بيانات المصمم</h2>
        <p class="muted">تعديل بيانات مصمم موجود دون فتح كل أجزاء الملف.</p>
    </a>
    <a class="panel module-card" href="{{ route('records.index') }}">
        <span class="eyebrow">05</span>
        <h2>كل ملفات المتابعة</h2>
        <p class="muted">الملف الكامل يجمع بيانات المصمم، المتابعة، الملاحظات، الوثائق، وسجل التغييرات.</p>
    </a>
    @if(auth()->user()->isAdmin())
        <a class="panel module-card" href="{{ route('users.index') }}">
            <span class="eyebrow">06</span>
            <h2>إدارة المستخدمين</h2>
            <p class="muted">حسابات مدير التصميم، المدير العام، والمصممين غير المستخدمين للنظام.</p>
        </a>
    @endif
</div>

<section class="panel" style="margin-top:16px">
    <h2>آخر سجلات المصممين</h2>
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
                    <tr><td colspan="6" class="muted">لا توجد سجلات مصممين بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
