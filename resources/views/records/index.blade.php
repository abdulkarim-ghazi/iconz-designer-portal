@extends('layouts.app')

@section('title', 'نشاط الموظفات')

@php
    $statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">نشاط مرتبط بالبطاقات</span>
        <h1>نشاط الموظفات</h1>
        <p class="muted">هذه صفحة داخلية لعرض سياقات العمل المرتبطة ببطاقات الموظفات، مثل زبون أو مشروع أو فترة متابعة.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('designers.index') }}">بطاقات الموظفات</a>
    </div>
</header>

<section class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث باسم الموظفة أو الزبون أو المشروع</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث باسم الموظفة، الزبون، أو المشروع">
        </div>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>الموظفة</th>
                    <th>الزبون</th>
                    <th>المشروع</th>
                    <th>الشهر</th>
                    <th>متوسط التقييم</th>
                    <th>الحالة</th>
                    <th>فتح</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr onclick="window.location='{{ route('records.show', $record) }}'" style="cursor:pointer">
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->job_title }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td>{{ $monthLabels[$record->current_month] }}</td>
                        <td><span class="badge green">{{ $record->totalScore() }}%</span></td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td><a class="btn {{ $canManage ? 'primary' : '' }}" href="{{ route('records.show', $record) }}">فتح</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">لا يوجد نشاط بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $records->links() }}</div>
</section>
@endsection
