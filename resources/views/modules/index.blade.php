@extends('layouts.app')

@section('title', $meta['title'])

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">موديول مستقل</span>
        <h1>{{ $meta['title'] }}</h1>
        <p class="muted">{{ $meta['description'] }}</p>
        <p class="muted">المسؤول: {{ $meta['owner'] }}. اختر ملف متابعة لمصمم معين ثم عدّل هذا الجزء فقط.</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.create') }}">إنشاء ملف متابعة</a>
        <a class="btn" href="{{ route('records.index') }}">كل الملفات</a>
    </div>
</header>

<section class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>اختيار المصمم أو الملف</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث باسم المصمم، الزبون، أو المشروع">
        </div>
    </form>

    <div class="form-grid" style="margin-bottom:14px">
        <div class="field">
            <label>فتح سريع حسب المصمم</label>
            <select id="quickRecordSelect">
                <option value="">اختر سجل مصمم</option>
                @foreach($records as $record)
                    <option value="{{ route('modules.edit', [$module, $record]) }}">
                        {{ $record->employee_name }}{{ $record->project_name ? ' - '.$record->project_name : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>&nbsp;</label>
            <button class="btn primary" type="button" onclick="if(document.getElementById('quickRecordSelect').value) window.location = document.getElementById('quickRecordSelect').value">فتح الموديول</button>
        </div>
    </div>

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
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->email }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                        <td><a class="btn primary" href="{{ route('modules.edit', [$module, $record]) }}">فتح الموديول</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">لا توجد ملفات متابعة بعد. أنشئ ملفاً أولاً ثم ارجع لهذا الموديول.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $records->links() }}</div>
</section>
@endsection
