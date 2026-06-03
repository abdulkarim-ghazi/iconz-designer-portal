@extends('layouts.app')

@section('title', 'سجلات المصممين')

@section('content')
@php($statusLabels = ['new'=>'جديد','in_progress'=>'قيد العمل','waiting_customer'=>'بانتظار الزبون','sent'=>'تم الإرسال','approved'=>'معتمد','closed'=>'مغلق'])
<header class="topbar">
    <div>
        <span class="eyebrow">iConz Designer Performance Portal</span>
        <h1>سجلات المصممين</h1>
        <p class="muted">كل سجل يظهر كصف في الجدول، وعند اختياره تفتح صفحة كاملة للتفاصيل والوثائق والتقييم.</p>
    </div>
    <div class="actions">
        <a class="btn primary" href="{{ route('records.create') }}">إضافة سجل</a>
    </div>
</header>

<div class="panel">
    <form method="GET" class="form-grid" style="margin-bottom:14px">
        <div class="field full">
            <label>بحث بالموظفة أو الزبون أو المشروع</label>
            <input name="search" value="{{ request('search') }}" placeholder="اكتب كلمة للبحث">
        </div>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>المصمم</th>
                    <th>الزبون</th>
                    <th>المشروع</th>
                    <th>الحالة</th>
                    <th>الشهر</th>
                    <th>متوسط التقييم</th>
                    <th>وثائق</th>
                    <th>آخر تحديث</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr onclick="window.location='{{ route('records.show', $record) }}'" style="cursor:pointer">
                        <td><strong>{{ $record->employee_name }}</strong><br><span class="muted">{{ $record->designer?->email }}</span></td>
                        <td>{{ $record->customer_name ?: '-' }}</td>
                        <td>{{ $record->project_name ?: '-' }}</td>
                        <td><span class="badge gold">{{ $statusLabels[$record->project_status] }}</span></td>
                        <td>{{ ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'][$record->current_month] }}</td>
                        <td><span class="badge green">{{ $record->totalScore() }}%</span></td>
                        <td>{{ $record->documents_count }}</td>
                        <td>{{ $record->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">لا توجد سجلات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $records->links() }}</div>
</div>
@endsection
