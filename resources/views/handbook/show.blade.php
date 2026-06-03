@extends('layouts.app')

@section('title', 'دليل المصممة')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">iConz Handbook</span>
        <h1>دليل تطوير المصممة داخل iConz</h1>
        <p class="muted">نسخة Laravel من الدليل الموجود في ملف handbook.html، مدمجة داخل النظام بعد تسجيل الدخول.</p>
    </div>
</header>
<div class="grid two">
    <section class="panel">
        <h2>توقعات الشركة</h2>
        <p class="muted">المصمم مسؤول عن فهم طلب الزبون، جودة التصميم، قابلية التنفيذ، وضوح ملفات الإنتاج، والتعاون مع الفريق حتى التسليم.</p>
        <ul class="grid">
            <li class="card panel">اسأل قبل البدء إذا كانت معلومات الزبون ناقصة.</li>
            <li class="card panel">راجع القياسات والنصوص والشعار قبل الإرسال.</li>
            <li class="card panel">وثق أي تأخير أو تعديل مهم داخل السجل.</li>
        </ul>
    </section>
    <section class="panel">
        <h2>خطة 3 أشهر</h2>
        <p><span class="badge gold">الشهر الأول</span> فهم النظام وآلية استلام المشاريع.</p>
        <p><span class="badge gold">الشهر الثاني</span> تقليل الأخطاء ورفع جودة الملفات.</p>
        <p><span class="badge gold">الشهر الثالث</span> استقلالية أكبر ومتابعة المشروع حتى التنفيذ.</p>
    </section>
</div>
<section class="panel" style="margin-top:16px">
    <h2>معايير التقييم</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>المحور</th><th>الوزن</th></tr></thead>
            <tbody>
                <tr><td>جودة التصميم والإبداع</td><td>15%</td></tr>
                <tr><td>دقة التفاصيل</td><td>10%</td></tr>
                <tr><td>قابلية التصميم للتنفيذ</td><td>15%</td></tr>
                <tr><td>سرعة الإنجاز والالتزام</td><td>10%</td></tr>
                <tr><td>فهم متطلبات الزبون</td><td>10%</td></tr>
                <tr><td>ملفات الإنتاج والطباعة</td><td>15%</td></tr>
                <tr><td>المتابعة والتعاون والمرونة</td><td>25%</td></tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
