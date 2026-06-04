@extends('layouts.app')

@php
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $avg = (int) round($record->monthlyEvaluations->avg('total_score') ?: 0);
    $errors = $record->weeklyEntries->where('production_error', true)->count() + $record->activityLogs->where('type', 'خطأ إنتاجي')->count();
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('title', 'بطاقة نشاط - '.$record->employee_name)

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">بطاقة نشاط</span>
        <h1>{{ $record->employee_name }}</h1>
        <p class="muted">{{ $record->customer_name ?: 'بدون زبون محدد' }} · {{ $record->project_name ?: 'بدون مشروع محدد' }}</p>
    </div>
    <div class="actions">
        @if($canManage)
            <a class="btn primary" href="{{ route('records.edit', $record) }}">تعديل السجل</a>
        @endif
        <a class="btn" href="{{ route('designers.index') }}">بطاقات الموظفات</a>
        @if($canManage)
            <form method="POST" action="{{ route('records.destroy', $record) }}" onsubmit="return confirm('حذف هذا النشاط؟')">
                @csrf @method('DELETE')
                <button class="btn danger" type="submit">حذف</button>
            </form>
        @endif
    </div>
</header>

@unless($canManage)
    <div class="module-note" style="margin-bottom:16px">أنت تشاهد الملف بوضع مراقبة الإدارة. التعديل والرفع والحذف من صلاحية مدير التصميم فقط.</div>
@endunless

<div class="grid metrics">
    <div class="card metric"><span>متوسط التقييم</span><strong>{{ $avg }}%</strong></div>
    <div class="card metric"><span>مرحلة التقييم</span><strong>{{ $monthLabels[$record->current_month] }}</strong></div>
    <div class="card metric"><span>أخطاء إنتاجية</span><strong>{{ $errors }}</strong></div>
    <div class="card metric"><span>حالة الملف</span><strong>{{ $statusLabels[$record->project_status] }}</strong></div>
</div>

<div class="grid three" style="margin-top:16px">
    <section class="panel module-card">
        <span class="eyebrow">موديول 01</span>
        <h2>بطاقة الموظفة</h2>
        <p class="muted">المسمى: {{ $record->job_title }}</p>
        <p class="muted">المدير: {{ $record->manager_name ?: '-' }}</p>
        <p class="muted">تاريخ المباشرة: {{ optional($record->start_date)->format('Y-m-d') ?: '-' }}</p>
    </section>
    <section class="panel module-card">
        <span class="eyebrow">موديول 02</span>
        <h2>الزبون والمشروع</h2>
        <p class="muted">الزبون: {{ $record->customer_name ?: '-' }}</p>
        <p class="muted">المشروع: {{ $record->project_name ?: '-' }}</p>
        <p class="muted">الحالة: {{ $statusLabels[$record->project_status] }}</p>
    </section>
    <section class="panel module-card">
        <span class="eyebrow">موديول 03</span>
        <h2>قرار الإدارة</h2>
        <p><span class="badge gold">{{ $record->final_decision ?: 'لم يتم اتخاذ القرار' }}</span></p>
        <p class="muted">{{ $record->decision_reason ?: 'لم يكتب سبب القرار بعد.' }}</p>
    </section>
</div>

<section class="panel" style="margin-top:16px">
    <h2>طلب الزبون وملاحظات العمل</h2>
    <div class="grid two">
        <div>
            <p><strong>طلب الزبون</strong></p>
            <p class="muted">{{ $record->customer_request ?: 'لا يوجد' }}</p>
        </div>
        <div>
            <p><strong>ملاحظات الموظفة</strong></p>
            <p class="muted">{{ $record->designer_notes ?: 'لا يوجد' }}</p>
        </div>
    </div>
    <p><strong>ملخص الإدارة</strong></p>
    <p class="muted">{{ $record->manager_summary ?: 'لا يوجد' }}</p>
</section>

<section class="panel" style="margin-top:16px">
    <h2>المتابعة الأسبوعية</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>الأسبوع</th><th>المشروع</th><th>نقطة إيجابية</th><th>نقطة سلبية</th><th>مرونة</th><th>خطأ إنتاجي</th><th>ملاحظة الإدارة</th></tr></thead>
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
                    <tr><td colspan="7" class="muted">لا توجد متابعة أسبوعية مسجلة.</td></tr>
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
                @forelse($record->monthlyEvaluations as $eval)
                    <tr>
                        <td>{{ $monthLabels[$eval->month_key] }}</td>
                        <td><span class="badge green">{{ $eval->total_score }}%</span></td>
                        <td>{{ $eval->manager_answers['summary'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">لا توجد تقييمات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>السجلات والملاحظات</h2>
    <div class="grid">
        @forelse($record->activityLogs as $log)
            <div class="timeline-item">
                <span class="badge {{ $log->type === 'خطأ إنتاجي' ? 'red' : 'green' }}">{{ $log->type }}</span>
                <strong>{{ $log->project ?: 'بدون مشروع' }}</strong>
                <p class="muted">{{ $log->note }}</p>
                <small>{{ optional($log->logged_at)->format('Y-m-d') }} · تأثير: {{ $log->impact ?: '-' }}</small>
            </div>
        @empty
            <p class="muted">لا توجد ملاحظات أداء مسجلة.</p>
        @endforelse
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>الوثائق والمعاينة</h2>
    @if($canManage)
        <form method="POST" action="{{ route('records.documents.store', $record) }}" enctype="multipart/form-data" class="form-grid" style="margin-bottom:14px">
            @csrf
            <div class="field"><label>عنوان اختياري</label><input name="title" placeholder="مثال: ملف إنتاج، صورة مرجعية، موافقة الزبون"></div>
            <div class="field"><label>رفع وثائق متعددة</label><input type="file" name="documents[]" multiple required></div>
            <div class="field full"><button class="btn primary" type="submit">رفع الوثائق</button></div>
        </form>
    @endif
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
                @if($canManage)
                    <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('حذف الوثيقة؟')">
                        @csrf @method('DELETE')
                        <button class="btn danger" type="submit">حذف</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">لا توجد وثائق بعد.</p>
        @endforelse
    </div>
</section>

<section class="panel" style="margin-top:16px">
    <h2>سجل التغييرات داخل الملف</h2>
    <div class="timeline">
        @forelse($record->changes->sortByDesc('created_at') as $change)
            <div class="timeline-item">
                <span class="badge blue">{{ $change->action }}</span>
                <strong>{{ $change->summary }}</strong>
                <p class="muted">بواسطة: {{ $change->user?->name ?: 'مستخدم محذوف' }}</p>
                <small>{{ $change->created_at->format('Y-m-d H:i') }}</small>
                @if(! empty($change->changes))
                    <details style="margin-top:8px">
                        <summary class="muted">عرض تفاصيل التغيير</summary>
                        <pre style="white-space:pre-wrap; direction:ltr; text-align:left">{{ json_encode($change->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </details>
                @endif
            </div>
        @empty
            <p class="muted">لا يوجد سجل تغييرات بعد.</p>
        @endforelse
    </div>
</section>
@endsection
