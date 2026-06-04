@extends('layouts.app')

@section('title', 'خطة 3 أشهر')

@section('content')
<section class="handbook-hero">
    <div>
        <span class="eyebrow">خطة 3 أشهر</span>
        <h1>خطة المتابعة والتطور داخل iConz</h1>
        <p class="muted">
            مرجع واضح لمدير التصميم والمدير العام يشرح كيف تتم متابعة المصممات: ما المتوقع، كيف تسجل الملاحظات، وكيف يتحول الأداء اليومي إلى تقييم عادل خلال فترة التجربة.
        </p>
    </div>
</section>

<div class="handbook-strip">
    <div class="handbook-stat">
        <strong>01</strong>
        <span class="eyebrow">فترة المتابعة</span>
        <p class="muted">ثلاثة أشهر قابلة للمراجعة حسب الأداء والالتزام.</p>
    </div>
    <div class="handbook-stat">
        <strong>02</strong>
        <span class="eyebrow">المسؤول عن التسجيل</span>
        <p class="muted">مدير التصميم يسجل بيانات المصممة والمتابعة والملاحظات.</p>
    </div>
    <div class="handbook-stat">
        <strong>03</strong>
        <span class="eyebrow">المراقبة</span>
        <p class="muted">المدير العام يرى الصورة الكاملة والنتائج والسجل.</p>
    </div>
    <div class="handbook-stat">
        <strong>04</strong>
        <span class="eyebrow">القرار</span>
        <p class="muted">يعتمد على الأدلة المسجلة، لا على الانطباع العام فقط.</p>
    </div>
</div>

<section class="grid two handbook-section">
    <div class="handbook-card">
        <span class="eyebrow">الفكرة الأساسية</span>
        <h2>ما الذي يعنيه الأداء الجيد؟</h2>
        <p class="muted">
            الأداء الجيد ليس تصميمًا جميلًا فقط. المطلوب أن تفهم المصممة طلب الزبون، تراعي القياسات والمواد، تجهز ملفًا قابلًا للتنفيذ، وتتعاون مع الفريق حتى يصل المشروع إلى نتيجة سليمة.
        </p>
    </div>
    <div class="handbook-card">
        <span class="eyebrow">طريقة الاستخدام</span>
        <h2>كيف يستخدم النظام هذا الدليل؟</h2>
        <p class="muted">
            عند إنشاء مصممة جديدة يتم فتح بطاقة موظفة لها. بعد ذلك تسجل المتابعة الأسبوعية وملاحظات الأداء بشكل منفصل، وكلها ترتبط بنفس الموظفة لتظهر للإدارة في لوحة واضحة.
        </p>
    </div>
</section>

<section class="panel handbook-section">
    <span class="eyebrow">مسار العمل</span>
    <h2>خطوات العمل المتوقعة من المصممة</h2>
    <div class="handbook-steps">
        <div class="handbook-step">
            <h3>استلام الطلب</h3>
            <p class="muted">قراءة اسم الزبون، المشروع، المقاسات، الموعد، المواد، وأي ملاحظات خاصة.</p>
        </div>
        <div class="handbook-step">
            <h3>السؤال قبل التنفيذ</h3>
            <p class="muted">عند نقص المعلومات يجب السؤال قبل البدء، بدل افتراض تفاصيل قد تسبب خطأ إنتاجيًا.</p>
        </div>
        <div class="handbook-step">
            <h3>التصميم بواقعية</h3>
            <p class="muted">مراعاة قابلية التنفيذ، السماكات، الإضاءة، الطباعة، المواد، والكلفة.</p>
        </div>
        <div class="handbook-step">
            <h3>المراجعة</h3>
            <p class="muted">تدقيق النصوص، المقاسات، الشعار، الألوان، والنسخة النهائية قبل الإرسال.</p>
        </div>
        <div class="handbook-step">
            <h3>ملفات الإنتاج</h3>
            <p class="muted">تجهيز ملفات واضحة للمعمل أو الطباعة مع ملاحظات قابلة للفهم والتنفيذ.</p>
        </div>
        <div class="handbook-step">
            <h3>المتابعة</h3>
            <p class="muted">متابعة التعديلات أو مشكلة التنفيذ عند الحاجة، وعدم ترك المشروع بمجرد إرسال التصميم.</p>
        </div>
    </div>
</section>

<section class="grid two handbook-section">
    <div class="panel">
        <span class="eyebrow">يرفع التقييم</span>
        <h2>نقاط إيجابية</h2>
        <ul class="handbook-list">
            <li>التسليم في الوقت المتفق عليه.</li>
            <li>تقليل الأخطاء من أسبوع لآخر.</li>
            <li>طرح أسئلة صحيحة قبل بدء التصميم.</li>
            <li>تجهيز ملفات إنتاج أوضح وأسهل على المعمل.</li>
            <li>التعامل مع الملاحظات كفرصة تحسين.</li>
        </ul>
    </div>
    <div class="panel">
        <span class="eyebrow">يخفض التقييم</span>
        <h2>نقاط سلبية</h2>
        <ul class="handbook-list">
            <li>تكرار الخطأ نفسه بعد التنبيه.</li>
            <li>إرسال تصميم غير قابل للتنفيذ.</li>
            <li>عدم مراجعة النصوص أو المقاسات أو الشعار.</li>
            <li>التأخير دون إبلاغ مدير التصميم.</li>
            <li>تحميل الآخرين مسؤولية خطأ ناتج عن التصميم أو نقص المراجعة.</li>
        </ul>
    </div>
</section>

<section class="panel handbook-section">
    <span class="eyebrow">محاور التقييم</span>
    <h2>كيف يتم تقييم المصممة؟</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>المحور</th>
                    <th>الوزن</th>
                    <th>ما الذي يتم ملاحظته؟</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>جودة التصميم والإبداع</td><td>15%</td><td>فكرة واضحة، شكل مناسب، وحل بصري قابل للاستخدام.</td></tr>
                <tr><td>دقة التفاصيل</td><td>10%</td><td>نصوص، ألوان، شعار، قياسات، ومراجعة قبل الإرسال.</td></tr>
                <tr><td>قابلية التصميم للتنفيذ</td><td>15%</td><td>اختيار حلول تناسب المواد، المعمل، الموقع، والموعد.</td></tr>
                <tr><td>سرعة الإنجاز والالتزام</td><td>10%</td><td>تسليم ضمن الوقت أو إبلاغ واضح عند وجود تأخير.</td></tr>
                <tr><td>فهم طلب الزبون</td><td>10%</td><td>ترجمة الطلب إلى تصميم صحيح دون افتراضات خطرة.</td></tr>
                <tr><td>ملفات الإنتاج والطباعة</td><td>15%</td><td>ملفات واضحة، مرتبة، ومفهومة لمن سينفذها.</td></tr>
                <tr><td>المتابعة مع الفريق</td><td>10%</td><td>تعاون مع المعمل أو الموقع عند وجود تعديل أو مشكلة.</td></tr>
                <tr><td>التعاون والمرونة</td><td>15%</td><td>استجابة عملية، قبول الملاحظات، وتحسن مستمر.</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section class="grid three handbook-section">
    <div class="handbook-card">
        <span class="badge gold">الشهر الأول</span>
        <h2>فهم النظام</h2>
        <p class="muted">تعلّم طريقة استلام المشاريع، طرح الأسئلة، وتجهيز الملفات الأساسية.</p>
    </div>
    <div class="handbook-card">
        <span class="badge gold">الشهر الثاني</span>
        <h2>تقليل الأخطاء</h2>
        <p class="muted">تحسين الجودة، تقليل التعديلات الناتجة عن سوء الفهم، وضبط الملفات.</p>
    </div>
    <div class="handbook-card">
        <span class="badge gold">الشهر الثالث</span>
        <h2>الاستقلالية</h2>
        <p class="muted">إدارة المهام بثقة أعلى، متابعة المشروع، وإظهار مرونة بدون فقدان التنظيم.</p>
    </div>
</section>

<section class="handbook-callout handbook-section">
    <h2>ملاحظة إدارية مهمة</h2>
    <p class="muted">
        قرار التثبيت أو الزيادة أو إنهاء التجربة يجب أن يكون مبنيًا على المتابعة الأسبوعية، ملاحظات الأداء، الملفات الداعمة، وتطور المصممة عبر الوقت. لذلك تسجيل المعلومة في وقتها أهم من تذكرها لاحقًا.
    </p>
</section>
@endsection
