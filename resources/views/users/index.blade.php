@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@php
    $roleLabels = [
        'admin' => 'مدير عام / مراقبة',
        'design_manager' => 'مدير التصميم / إدخال وتعديل',
        'designer' => 'مصمم / سجل داخلي',
    ];
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>إدارة المستخدمين</h1>
        <p class="muted">إضافة حسابات الإدارة ومدير التصميم، مع إبقاء المصممين كسجلات داخلية عند الحاجة.</p>
    </div>
    <div class="actions"><a class="btn primary" href="{{ route('users.create') }}">إضافة مستخدم</a></div>
</header>
<section class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>الاسم</th><th>البريد</th><th>الدور</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge">{{ $roleLabels[$user->role] ?? $user->role }}</span></td>
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
