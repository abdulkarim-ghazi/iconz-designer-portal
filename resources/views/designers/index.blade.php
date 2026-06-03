@extends('layouts.app')

@section('title', 'المصممون')

@php
    $statusLabels = ['active' => 'نشط', 'on_hold' => 'مؤجل', 'completed' => 'مكتمل', 'inactive' => 'غير نشط'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">قسم المصممين</span>
        <h1>المصممون</h1>
        <p class="muted">هذا القسم يحتوي بيانات المصممين كموظفين تتم متابعتهم. لا يوجد مصمم يدخل النظام.</p>
    </div>
    <div class="actions">
        @if($canManage)
            <a class="btn primary" href="{{ route('designers.create') }}">إضافة مصمم</a>
        @endif
        <a class="btn" href="{{ route('records.index') }}">ملفات المتابعة</a>
    </div>
</header>

<section class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث باسم المصمم أو البريد أو المسمى</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث عن مصمم">
        </div>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>المصمم</th>
                    <th>المسمى</th>
                    <th>المدير المباشر</th>
                    <th>مرحلة التقييم</th>
                    <th>الحالة</th>
                    <th>ملفات متابعة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designers as $designer)
                    <tr>
                        <td><strong>{{ $designer->name }}</strong><br><span class="muted">{{ $designer->email ?: '-' }}</span></td>
                        <td>{{ $designer->job_title }}</td>
                        <td>{{ $designer->direct_manager ?: '-' }}</td>
                        <td>{{ $monthLabels[$designer->current_month] }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$designer->status] }}</span></td>
                        <td>{{ $designer->records_count }}</td>
                        <td class="actions">
                            <a class="btn" href="{{ route('designers.show', $designer) }}">عرض</a>
                            @if($canManage)
                                <a class="btn primary" href="{{ route('designers.edit', $designer) }}">تعديل</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد بيانات مصممين بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $designers->links() }}</div>
</section>
@endsection
