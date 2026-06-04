@extends('layouts.app')

@section('title', 'بطاقات الموظفات')

@php
    $statusLabels = ['active' => 'نشط', 'on_hold' => 'مؤجل', 'completed' => 'مكتمل', 'inactive' => 'غير نشط'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">بطاقة الموظفة</span>
        <h1>بطاقات الموظفات</h1>
        <p class="muted">هذه هي نقطة البداية. مدير التصميم ينشئ بطاقة لكل موظفة، ومنها تتم المتابعة الأسبوعية والتقييم الشهري والسجلات وقرار الزيادة.</p>
    </div>
    <div class="actions">
        @if($canManage)
            <a class="btn primary" href="{{ route('designers.create') }}">إضافة موظفة</a>
        @endif
    </div>
</header>

<section class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث باسم الموظفة أو البريد أو المسمى</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث عن موظفة">
        </div>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>الموظفة</th>
                    <th>المسمى</th>
                    <th>المدير المباشر</th>
                    <th>الشهر الحالي</th>
                    <th>الحالة</th>
                    <th>نشاط مرتبط</th>
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
                            <a class="btn" href="{{ route('designers.show', $designer) }}">فتح البطاقة</a>
                            @if($canManage)
                                <a class="btn primary" href="{{ route('designers.edit', $designer) }}">تعديل</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا توجد بطاقات موظفات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $designers->links() }}</div>
</section>
@endsection
