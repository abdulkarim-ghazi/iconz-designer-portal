@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@php
    $roleLabels = [
        'admin' => 'مدير عام / مراقبة',
        'design_manager' => 'مدير التصميم / إدخال وتعديل',
        'designer' => 'مصممة / مشاهدة تقييماتها فقط',
    ];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>إدارة المستخدمين</h1>
        <p class="muted">إدارة حسابات الدخول: المدير العام، مدير التصميم، وحسابات المصممات المحدودة.</p>
    </div>
    <div class="actions"><a class="btn primary" href="{{ route('users.create') }}">إضافة مستخدم</a></div>
</header>
<section class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>الاسم</th><th>البريد</th><th>الدور</th><th>بطاقة الموظفة</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge">{{ $roleLabels[$user->role] ?? $user->role }}</span></td>
                        <td>{{ $user->designer?->name ?: '-' }}</td>
                        <td><span class="badge {{ $user->is_active ? 'green' : 'red' }}">{{ $user->is_active ? 'مفعل' : 'موقوف' }}</span></td>
                        <td class="actions">
                            <a class="btn" href="{{ route('users.edit', $user) }}">تعديل</a>
                            @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('حذف المستخدم؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn danger" type="submit">حذف</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $users->links() }}</div>
</section>
@endsection
