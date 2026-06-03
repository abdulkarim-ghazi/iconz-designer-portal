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
                    <p>برنامج متابعة المصممين وتوثيق التعامل مع الزبائن والإرسال للإدارة.</p>
                </div>
                <nav class="nav">
                    <a class="{{ request()->routeIs('records.*') ? 'active' : '' }}" href="{{ route('records.index') }}"><span>السجلات</span><b>01</b></a>
                    <a class="{{ request()->routeIs('handbook') ? 'active' : '' }}" href="{{ route('handbook') }}"><span>دليل المصممة</span><b>02</b></a>
                    @if(auth()->user()->isAdmin())
                        <a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span>إدارة المستخدمين</span><b>03</b></a>
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
</body>
</html>
