<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'iConz Designer Portal')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @auth
        <div class="app">
            <aside class="sidebar">
                <div class="brand">
                    <div class="logo"><span>i</span>Conz <small>Portal</small></div>
                    <p>منظومة متابعة المصممين، طلبات الزبائن، الوثائق، التقييمات، وسجل التغييرات.</p>
                </div>
                <nav class="nav">
                    <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>لوحة المتابعة</span><b>01</b></a>
                    <a class="{{ request()->routeIs('records.index') || request()->routeIs('records.show') || request()->routeIs('records.edit') ? 'active' : '' }}" href="{{ route('records.index') }}"><span>ملفات المتابعة</span><b>02</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/designer-data*') ? 'active' : '' }}" href="{{ route('modules.index', 'designer-data') }}"><span>بيانات المصمم</span><b>03</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/weekly-followup*') ? 'active' : '' }}" href="{{ route('modules.index', 'weekly-followup') }}"><span>المتابعة الأسبوعية</span><b>04</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/performance-notes*') ? 'active' : '' }}" href="{{ route('modules.index', 'performance-notes') }}"><span>ملاحظات الأداء</span><b>05</b></a>
                    <a class="{{ request()->routeIs('records.create') ? 'active' : '' }}" href="{{ route('records.create') }}"><span>إنشاء سجل مصمم</span><b>06</b></a>
                    <a class="{{ request()->routeIs('handbook') ? 'active' : '' }}" href="{{ route('handbook') }}"><span>دليل المصممة</span><b>07</b></a>
                    @if(auth()->user()->isAdmin())
                        <a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span>إدارة المستخدمين</span><b>08</b></a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><span>تسجيل الخروج</span><b>--</b></button>
                    </form>
                </nav>
            </aside>
            <main class="main">
                @if(session('status'))
                    <div class="alert">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
    @stack('scripts')
</body>
</html>
