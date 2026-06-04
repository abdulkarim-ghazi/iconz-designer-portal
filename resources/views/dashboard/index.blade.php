@extends('layouts.app')

@section('title', 'لوحة المتابعة')

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $canManage = auth()->user()->canManageDesignerData();
    $isAdmin = auth()->user()->isAdmin();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $isAdmin ? 'مركز مراقبة الإدارة' : 'مساحة عمل مدير التصميم' }}</span>
        <h1>{{ $isAdmin ? 'مراقبة أداء المصممين' : 'لوحة متابعة المصممين' }}</h1>
        <p class="muted">
            @if($isAdmin)
                المدير العام يراجع البيانات التي ينشئها مدير التصميم: سجلات التقييم، المتابعات الأسبوعية، ملاحظات الأداء، الوثائق، وسجل التغييرات.
            @else
                مدير التصميم ينشئ المصممين وسجلات التقييم، ثم يضيف المتابعة الأسبوعية وملاحظات الأداء والوثائق الداعمة.
            @endif
        </p>
    </div>
    <div class="actions">
        @if($canManage)
            <a class="btn primary" href="{{ route('records.create') }}">إنشاء سجل تقييم</a>
            <a class="btn" href="{{ route('designers.index') }}">قسم المصممين</a>
        @endif
        <a class="btn" href="{{ route('records.index') }}">كل سجلات التقييم</a>
    </div>
</header>

<div class="grid metrics">
    <div class="card metric"><span>المصممون المسجلون</span><strong>{{ $totalDesigners }}</strong></div>
    <div class="card metric"><span>سجلات التقييم</span><strong>{{ $totalRecords }}</strong></div>
    <div class="card metric"><span>متابعات أسبوعية</span><strong>{{ $weeklyEntriesCount }}</strong></div>
    <div class="card metric"><span>ملاحظات أداء</span><strong>{{ $activityLogsCount }}</strong></div>
</div>

@if($canManage)
    <div class="grid three" style="margin-top:16px">
        <a class="panel module-card" href="{{ route('records.create') }}">
            <span class="eyebrow">01</span>
            <h2>إنشاء سجل تقييم</h2>
            <p class="muted">افتح دورة تقييم لمصمم موجود. هذا ليس إنشاء مصمم جديد.</p>
        </a>
        <a class="panel module-card" href="{{ route('designers.index') }}">
            <span class="eyebrow">02</span>
            <h2>قسم المصممين</h2>
            <p class="muted">أدر بيانات المصممين كموظفين تتم متابعتهم. لا يوجد مصمم يدخل النظام.</p>
        </a>
        <a class="panel module-card" href="{{ route('modules.index', 'weekly-followup') }}">
            <span class="eyebrow">03</span>
            <h2>المتابعة الأسبوعية</h2>
            <p class="muted">اختر المصمم أو سجل التقييم ثم أضف متابعة الأسبوع والملفات الداعمة.</p>
        </a>
        <a class="panel module-card" href="{{ route('modules.index', 'performance-notes') }}">
            <span class="eyebrow">04</span>
            <h2>ملاحظات الأداء والعمل</h2>
            <p class="muted">اربط ملاحظة أداء أو خطأ أو نقطة إيجابية بمصمم وسجل تقييم محدد.</p>
        </a>
        <a class="panel module-card" href="{{ route('modules.index', 'monthly-evaluation') }}">
            <span class="eyebrow">05</span>
            <h2>التقييم الشهري</h2>
            <p class="muted">اختر المصمم ثم سجل نقاط الشهر حسب المحاور والأوزان المعتمدة.</p>
        </a>
        <a class="panel module-card" href="{{ route('modules.index', 'management-decision') }}">
            <span class="eyebrow">06</span>
            <h2>قرار الإدارة والزيادة</h2>
            <p class="muted">سجل قرار التثبيت أو التمديد أو الزيادة مع السبب والخطة القادمة.</p>
        </a>
        <a class="panel module-card" href="{{ route('records.index') }}">
            <span class="eyebrow">07</span>
            <h2>كل سجلات التقييم</h2>
            <p class="muted">السجل الكامل يجمع المتابعة والملاحظات والتقييمات والوثائق وسجل التغييرات.</p>
        </a>
    </div>
@else
    <section class="panel" style="margin-top:16px">
        <h2>نطاق صلاحية الأدمن</h2>
        <div class="grid three">
            <div class="timeline-item"><strong>يراقب</strong><p class="muted">كل سجلات التقييم والنتائج والوثائق وسجل التغييرات.</p></div>
            <div class="timeline-item"><strong>يراجع</strong><p class="muted">من أضاف البيانات ومتى، وما الذي تغير داخل كل ملف.</p></div>
            <div class="timeline-item"><strong>لا ينشئ بيانات تشغيلية</strong><p class="muted">إنشاء المصممين والمتابعات وظيفة مدير التصميم.</p></div>
        </div>
    </section>
@endif

<section class="panel" style="margin-top:16px">
    <h2>{{ $isAdmin ? 'مؤشرات المصممين للإدارة' : 'نتائج المصممين' }}</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>المصمم</th>
                    <th>أنشأه</th>
                    <th>مرحلة المتابعة</th>
                    <th>متوسط التقييم</th>
                    <th>متابعات أسبوعية</th>
                    <th>ملاحظات أداء</th>
                    <th>ملفات داعمة</th>
                    <th>فتح</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designerPerformance as $record)
                    <tr>
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->job_title }}</span></td>
                        <td>{{ $record->creator?->name ?: '-' }}</td>
                        <td>{{ $monthLabels[$record->current_month] }}</td>
                        <td><span class="badge green">{{ $record->totalScore() }}%</span></td>
                        <td>{{ $record->weekly_entries_count }}</td>
                        <td>{{ $record->activity_logs_count }}</td>
                        <td>{{ $record->documents_count }}</td>
                        <td><a class="btn" href="{{ route('records.show', $record) }}">السجل الكامل</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا توجد نتائج بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@if($isAdmin)
    <section class="panel" style="margin-top:16px">
        <h2>آخر تغييرات مدير التصميم</h2>
        <div class="timeline">
            @forelse($latestChanges as $change)
                <a class="timeline-item" href="{{ route('records.show', $change->record) }}">
                    <span class="badge blue">{{ $change->action }}</span>
                    <strong>{{ $change->record?->employee_name ?: 'ملف محذوف' }}</strong>
                    <p class="muted">{{ $change->summary }}</p>
                    <small>بواسطة: {{ $change->user?->name ?: '-' }} · {{ $change->created_at->format('Y-m-d H:i') }}</small>
                </a>
            @empty
                <p class="muted">لا يوجد سجل تغييرات بعد.</p>
            @endforelse
        </div>
    </section>
@endif

<section class="panel" style="margin-top:16px">
    <h2>آخر سجلات التقييم</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>المصمم</th><th>الزبون</th><th>المشروع</th><th>الحالة</th><th>أنشأه</th><th>وثائق</th><th>آخر تحديث</th></tr></thead>
            <tbody>
                @forelse($recentRecords as $record)
                    <tr onclick="window.location='{{ route('records.show', $record) }}'" style="cursor:pointer">
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->job_title }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ $record->creator?->name ?: '-' }}</td>
                        <td>{{ $record->documents_count }}</td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد سجلات تقييم بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
