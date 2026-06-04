@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="login-page">
    <div class="panel login-card">
        <div class="login-brand">
            <div class="logo"><span>i</span>Conz <small>Portal</small></div>
        </div>
        <form method="POST" action="{{ route('login.store') }}" class="grid">
            @csrf
            <div class="field">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
            <label><input type="checkbox" name="remember" value="1" style="width:auto; min-height:auto"> تذكرني</label>
            <button class="btn primary" type="submit">دخول</button>
        </form>
    </div>
</div>
@endsection
