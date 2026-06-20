@extends('layouts.app')

@section('title', $mode === 'create' ? 'إضافة مستخدم' : 'تعديل مستخدم')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">Admin</span>
        <h1>{{ $mode === 'create' ? 'إضافة مستخدم' : 'تعديل مستخدم' }}</h1>
        <p class="muted">الأدمن يدير حسابات الدخول. حساب المصممة يرتبط ببطاقة موظفة واحدة ويشاهد تقييماته فقط.</p>
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
            <select name="role" id="roleSelect">
                <option value="admin" @selected(old('role', $user->role)==='admin')>مدير عام / مراقبة</option>
                <option value="design_manager" @selected(old('role', $user->role)==='design_manager')>مدير التصميم / إدخال وتعديل</option>
                <option value="designer" @selected(old('role', $user->role)==='designer')>مصممة / مشاهدة تقييماتها فقط</option>
            </select>
            @error('role')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="field full" id="designerField">
            <label>ربط الحساب ببطاقة موظفة</label>
            <select name="designer_id">
                <option value="">اختر بطاقة الموظفة</option>
                @foreach($designers as $designer)
                    <option value="{{ $designer->id }}" @selected(old('designer_id', $user->designer_id) == $designer->id)>{{ $designer->name }} - {{ $designer->job_title }}</option>
                @endforeach
            </select>
            @error('designer_id')<span class="error">{{ $message }}</span>@enderror
        </div>
        <label class="field full"><span>الحالة</span><span><input type="checkbox" name="is_active" value="1" style="width:auto; min-height:auto" @checked(old('is_active', $user->is_active))> حساب مفعل</span></label>
    </div>
    <div class="actions" style="margin-top:14px">
        <button class="btn primary" type="submit">حفظ</button>
        <a class="btn" href="{{ route('users.index') }}">رجوع</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const roleSelect = document.getElementById('roleSelect');
    const designerField = document.getElementById('designerField');

    function syncDesignerField() {
        designerField.style.display = roleSelect.value === 'designer' ? 'grid' : 'none';
    }

    roleSelect.addEventListener('change', syncDesignerField);
    syncDesignerField();
</script>
@endpush
