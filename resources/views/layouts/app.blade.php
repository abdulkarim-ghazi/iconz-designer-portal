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
        @php
            $canManage = auth()->user()->canManageDesignerData();
            $isAdmin = auth()->user()->isAdmin();
        @endphp
        <div class="app">
            <aside class="sidebar">
                <div class="brand">
                    <div class="logo"><span>i</span>Conz <small>Portal</small></div>
                    <p>منظومة متابعة الموظفات، الوثائق، التقييمات، وسجل التغييرات.</p>
                </div>
                <nav class="nav">
                    <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>الرئيسية</span><b>01</b></a>
                    <a class="{{ request()->routeIs('designers.*') || request()->routeIs('records.*') ? 'active' : '' }}" href="{{ route('designers.index') }}"><span>بطاقة الموظفة</span><b>02</b></a>
                    <a class="{{ request()->routeIs('plan') ? 'active' : '' }}" href="{{ route('plan') }}"><span>خطة 3 أشهر</span><b>03</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/weekly-followup*') ? 'active' : '' }}" href="{{ route('modules.index', 'weekly-followup') }}"><span>Scoreboard أسبوعي</span><b>04</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/monthly-evaluation*') ? 'active' : '' }}" href="{{ route('modules.index', 'monthly-evaluation') }}"><span>التقييم الشهري</span><b>05</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/performance-notes*') ? 'active' : '' }}" href="{{ route('modules.index', 'performance-notes') }}"><span>السجلات والملاحظات</span><b>06</b></a>
                    <a class="{{ request()->fullUrlIs('*modules/management-decision*') ? 'active' : '' }}" href="{{ route('modules.index', 'management-decision') }}"><span>قرار الزيادة</span><b>07</b></a>
                    <a class="{{ request()->routeIs('handbook') ? 'active' : '' }}" href="{{ route('handbook') }}"><span>الهاندبوك</span><b>08</b></a>
                    @if($canManage)
                        <a class="{{ request()->routeIs('designers.create') ? 'active' : '' }}" href="{{ route('designers.create') }}"><span>إضافة موظفة</span><b>09</b></a>
                    @endif
                    @if($isAdmin)
                        <a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span>إدارة المستخدمين</span><b>10</b></a>
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
