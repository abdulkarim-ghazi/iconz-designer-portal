@extends('layouts.app')

@section('title', $mode === 'create' ? 'إضافة مستخدم' : 'تعديل مستخدم')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>{{ $mode === 'create' ? 'إضافة مستخدم' : 'تعديل مستخدم' }}</h1>
        <p class="muted">الأدمن يراقب ويدير المستخدمين، ومدير التصميم ينشئ ويتابع بيانات المصممين.</p>
    </div>
</header>
<form method="POST" action="{{ $mode === 'create' ? route('users.store') : route('users.update', $user) }}" class="panel">
    @csrf
    @if($mode === 'edit') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label>الاسم</label><input name="name" value="{{ old('name', $user->name) }}" required>@error('name')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label>البريد الإلكتروني</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label>كلمة المرور</label><input type="password" name="password" {{ $mode === 'create' ? 'required' : '' }}>@error('password')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field">
            <label>الدور</label>
            <select name="role">
                <option value="admin" @selected(old('role', $user->role)==='admin')>مدير عام / مراقبة</option>
                <option value="design_manager" @selected(old('role', $user->role)==='design_manager')>مدير التصميم / إدخال وتعديل</option>
                <option value="designer" @selected(old('role', $user->role)==='designer')>مصمم / سجل داخلي</option>
            </select>
        </div>
        <label class="field full"><span>الحالة</span><span><input type="checkbox" name="is_active" value="1" style="width:auto; min-height:auto" @checked(old('is_active', $user->is_active))> حساب مفعل</span></label>
    </div>
    <div class="actions" style="margin-top:14px">
        <button class="btn primary" type="submit">حفظ</button>
        <a class="btn" href="{{ route('users.index') }}">رجوع</a>
    </div>
</form>
@endsection
