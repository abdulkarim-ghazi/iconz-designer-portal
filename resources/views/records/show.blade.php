@extends('layouts.app')

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $avg = (int) round($record->monthlyEvaluations->avg('total_score') ?: 0);
    $errors = $record->weeklyEntries->where('production_error', true)->count() + $record->activityLogs->where('type', 'خطأ إنتاجي')->count();
@endphp

@section('title', 'سجل '.$record->employee_name)

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $record->designer?->name }}</span>
        <h1>{{ $record->employee_name }}</h1>
        <p class="muted">{{ $record->customer_name ?: 'بدون زبون محدد' }} · {{ $record->project_name ?: 'بدون مشروع محدد' }}</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.edit', $record) }}">تعديل</a>
        <a class="btn" href="{{ route('records.index') }}">رجوع للجدول</a>
        @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('records.destroy', $record) }}" onsubmit="return confirm('حذف السجل؟')">
                @csrf @method('DELETE')
                <button class="btn danger" type="submit">حذف</button>
            </form>
        @endif
    </div>
</header>

<div class="grid metrics">
    <div class="card metric"><span>متوسط التقييم</span><strong>{{ $avg }}%</strong></div>
    <div class="card metric"><span>الشهر الحالي</span><strong>{{ $monthLabels[$record->current_month] }}</strong></div>
    <div class="card metric"><span>أخطاء إنتاجية</span><strong>{{ $errors }}</strong></div>
    <div class="card metric"><span>حالة المشروع</span><strong>{{ $statusLabels[$record->project_status] }}</strong></div>
</div>

<div class="grid two" style="margin-top:16px">
    <section class="panel">
        <h2>بيانات الزبون والمشروع</h2>
        <p><strong>طلب الزبون:</strong></p>
        <p class="muted">{{ $record->customer_request ?: 'لا يوجد' }}</p>
        <p><strong>ملاحظات المصمم:</strong></p>
        <p class="muted">{{ $record->designer_notes ?: 'لا يوجد' }}</p>
        <p><strong>ملخص الإدارة:</strong></p>
        <p class="muted">{{ $record->manager_summary ?: 'لا يوجد' }}</p>
    </section>
    <section class="panel">
        <h2>قرار الإدارة</h2>
        <p><span class="badge gold">{{ $record->final_decision }}</span></p>
        <p class="muted">{{ $record->decision_reason ?: 'لم يكتب سبب القرار بعد.' }}</p>
        <p><strong>الخطة القادمة:</strong></p>
        <p class="muted">{{ $record->next_plan ?: 'لا توجد خطة مسجلة.' }}</p>
    </section>
</div>

<section class="panel" style="margin-top:16px">
    <h2>Scoreboard أسبوعي</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>الأسبوع</th><th>المشروع</th><th>إيجابي</th><th>سلبي</th><th>مرونة</th><th>خطأ إنتاجي</th><th>ملاحظة الإدارة</th></tr></thead>
            <tbody>
                @forelse($record->weeklyEntries as $row)
                    <tr>
                        <td><strong>{{ $row->week_label }}</strong></td>
                        <td>{{ $row->project ?: '-' }}</td>
                        <td>{{ $row->positive ?: '-' }}</td>
                        <td>{{ $row->negative ?: '-' }}</td>
                        <td><span class="badge">{{ $row->flexibility ?: '-' }}</span></td>
                        <td><span class="badge {{ $row->production_error ? 'red' : 'green' }}">{{ $row->production_error ? 'نعم' : 'لا' }}</span></td>
                        <td>{{ $row->manager_note ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد أسابيع مسجلة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>التقييم الشهري</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>الشهر</th><th>المجموع</th><th>ملاحظات الإدارة</th></tr></thead>
            <tbody>
                @foreach($record->monthlyEvaluations as $eval)
                    <tr>
                        <td>{{ $monthLabels[$eval->month_key] }}</td>
                        <td><span class="badge green">{{ $eval->total_score }}%</span></td>
                        <td>{{ $eval->manager_answers['summary'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>السجلات والملاحظات</h2>
    <div class="grid">
        @forelse($record->activityLogs as $log)
            <div class="card panel">
                <span class="badge {{ $log->type === 'خطأ إنتاجي' ? 'red' : 'green' }}">{{ $log->type }}</span>
                <strong>{{ $log->project ?: 'بدون مشروع' }}</strong>
                <p class="muted">{{ $log->note }}</p>
                <small class="muted">{{ optional($log->logged_at)->format('Y-m-d') }} · تأثير: {{ $log->impact ?: '-' }}</small>
            </div>
        @empty
            <p class="muted">لا توجد ملاحظات مسجلة.</p>
        @endforelse
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>الوثائق</h2>
    <form method="POST" action="{{ route('records.documents.store', $record) }}" enctype="multipart/form-data" class="form-grid" style="margin-bottom:14px">
        @csrf
        <div class="field"><label>عنوان اختياري</label><input name="title" placeholder="مثال: ملف إنتاج، صورة مرجعية، موافقة الزبون"></div>
        <div class="field"><label>رفع وثائق متعددة</label><input type="file" name="documents[]" multiple required></div>
        <div class="field full"><button class="btn primary" type="submit">رفع الوثائق</button></div>
    </form>
    <div class="doc-grid">
        @forelse($record->documents as $document)
            <div class="card panel">
                <a href="{{ route('documents.show', $document) }}" target="_blank" title="فتح كامل">
                    <div class="doc-preview">
                        @if(str_starts_with($document->mime_type ?? '', 'image/'))
                            <img src="{{ route('documents.show', $document) }}" alt="{{ $document->title }}">
                        @elseif($document->mime_type === 'application/pdf')
                            <iframe src="{{ route('documents.show', $document) }}"></iframe>
                        @else
                            <span class="badge">ملف</span>
                        @endif
                    </div>
                    <h3 style="margin-top:10px">{{ $document->title ?: $document->original_name }}</h3>
                    <p class="muted">{{ $document->original_name }}</p>
                </a>
                <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('حذف الوثيقة؟')">
                    @csrf @method('DELETE')
                    <button class="btn danger" type="submit">حذف</button>
                </form>
            </div>
        @empty
            <p class="muted">لا توجد وثائق بعد.</p>
        @endforelse
    </div>
</section>
@endsection
