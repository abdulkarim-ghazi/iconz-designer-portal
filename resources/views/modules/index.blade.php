@extends('layouts.app')

@section('title', $meta['title'])

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $canManage = auth()->user()->canManageDesignerData();
    $requiresDesignerStart = in_array($module, ['weekly-followup', 'performance-notes', 'monthly-evaluation', 'management-decision'], true);
    $startLabels = [
        'weekly-followup' => 'إنشاء / فتح متابعة أسبوعية',
        'performance-notes' => 'إنشاء / فتح ملاحظة أداء',
        'monthly-evaluation' => 'إنشاء / فتح تقييم شهري',
        'management-decision' => 'إنشاء / فتح قرار الإدارة والزيادة',
    ];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $canManage ? 'موديول تنفيذي' : 'موديول مراقبة' }}</span>
        <h1>{{ $meta['title'] }}</h1>
        <p class="muted">{{ $meta['description'] }}</p>
        <p class="muted">المسؤول عن الإدخال: {{ $meta['owner'] }}. الأدمن يراجع فقط.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('designers.index') }}">قسم المصممين</a>
        <a class="btn" href="{{ route('records.index') }}">كل الملفات</a>
    </div>
</header>

@if($canManage && $requiresDesignerStart)
    <section class="panel" style="margin-bottom:16px">
        <h2>اختيار المصمم</h2>
        <p class="muted">اختر المصمم أولاً. إذا لم يكن لديه ملف متابعة مفتوح، سينشئ النظام ملفاً أساسياً ويربط هذا الموديول به.</p>
        @if($designers->isEmpty())
            <div class="alert">
                لا يوجد مصممون بعد. أضف مصمماً أولاً من قسم المصممين.
                <a class="btn primary" href="{{ route('designers.create') }}" style="margin-right:10px">إضافة مصمم</a>
            </div>
        @else
            <form method="POST" action="{{ route('modules.start', $module) }}" class="form-grid">
                @csrf
                <div class="field full">
                    <label>المصمم</label>
                    <select name="designer_id" required>
                        <option value="">اختر المصمم</option>
                        @foreach($designers as $designer)
                            <option value="{{ $designer->id }}">{{ $designer->name }} - {{ $designer->job_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field full">
                    <button class="btn primary" type="submit">{{ $startLabels[$module] }}</button>
                </div>
            </form>
        @endif
    </section>
@endif

<section class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث باسم المصمم أو الزبون أو المشروع</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث باسم المصمم، الزبون، أو المشروع">
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
                    <th>آخر تحديث</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->job_title }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                        <td><a class="btn {{ $canManage ? 'primary' : '' }}" href="{{ route('modules.edit', [$module, $record]) }}">{{ $canManage ? 'فتح' : 'عرض' }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">لا توجد ملفات متابعة بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $records->links() }}</div>
</section>
@endsection
