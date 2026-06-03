@extends('layouts.app')

@section('title', 'إنشاء مصمم جديد')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">مصمم</span>
        <h1>إنشاء مصمم جديد</h1>
        <p class="muted">هذه الصفحة تنشئ اسم مصمم يمكن ربط ملفات المتابعة به. هذا لا يعني أن المصمم سيدخل للنظام.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('records.create') }}">إنشاء ملف متابعة</a>
        <a class="btn" href="{{ route('records.index') }}">كل الملفات</a>
    </div>
</header>

@if($errors->any())
    <div class="alert">راجع بيانات المصمم قبل الحفظ.</div>
@endif

<form method="POST" action="{{ route('designers.store') }}" class="panel">
    @csrf
    <div class="form-grid">
        <div class="field">
            <label>اسم المصمم</label>
            <input name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="اختياري">
        </div>
    </div>
    <div class="actions" style="margin-top:14px">
        <button class="btn primary" type="submit">إنشاء المصمم</button>
        <a class="btn" href="{{ route('records.create') }}">إلغاء</a>
    </div>
</form>
@endsection
