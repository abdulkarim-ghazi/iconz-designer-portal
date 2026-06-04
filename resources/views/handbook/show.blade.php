@extends('layouts.app')

@section('title', 'الهاندبوك')

@section('content')
<section class="handbook-hero">
    <div>
        <span class="eyebrow">مرجع مطالعة</span>
        <h1>الهاندبوك</h1>
        <p class="muted">
            دليل مختصر للمطالعة يساعد الموظفة ومدير التصميم على فهم طريقة العمل اليومية داخل iConz: من استلام المشروع، إلى التصميم، إلى ملفات الإنتاج، ثم المتابعة حتى النتيجة النهائية.
        </p>
    </div>
</section>

<div class="module-tabs">
    <a href="#purpose">الفكرة</a>
    <a href="#expectations">التوقعات</a>
    <a href="#workflow">خطوات العمل</a>
    <a href="#execution">قابلية التنفيذ</a>
    <a href="#evidence">الأدلة</a>
    <a href="#raise">الزيادة</a>
</div>

<section id="purpose" class="panel handbook-section">
    <span class="eyebrow">الفكرة الأساسية</span>
    <h2>ما الهدف من الهاندبوك؟</h2>
    <div class="grid two">
        <div class="handbook-card">
            <h3>مرجع هادئ</h3>
            <p class="muted">ليس المقصود منه الضغط أو التعقيد، بل توضيح ما الذي يجعل العمل ناجحاً وقابلاً للقياس.</p>
        </div>
        <div class="handbook-card">
            <h3>لغة مشتركة</h3>
            <p class="muted">يساعد الموظفة والمدير على استخدام نفس المعايير عند الحديث عن الجودة، الأخطاء، المرونة، وقرار الزيادة.</p>
        </div>
    </div>
</section>

<section id="expectations" class="panel handbook-section">
    <span class="eyebrow">التوقعات</span>
    <h2>ما المتوقع من الموظفة؟</h2>
    <ul class="handbook-list">
        <li>فهم طلب الزبون قبل البدء وعدم افتراض التفاصيل الناقصة.</li>
        <li>طرح الأسئلة الصحيحة عندما تكون المعلومات غير واضحة.</li>
        <li>مراجعة النصوص، القياسات، الشعار، والألوان قبل الإرسال.</li>
        <li>تجهيز ملفات إنتاج أو طباعة واضحة ومفهومة.</li>
        <li>التعاون مع المعمل أو الموقع عند وجود تعديل أو مشكلة تنفيذ.</li>
    </ul>
</section>

<section id="workflow" class="panel handbook-section">
    <span class="eyebrow">سير العمل</span>
    <h2>خطوات العمل الصحيحة</h2>
    <div class="handbook-steps">
        <div class="handbook-step"><h3>استلام المشروع</h3><p class="muted">قراءة الطلب، الزبون، المكان، المقاسات، الموعد، والمواد.</p></div>
        <div class="handbook-step"><h3>طرح الأسئلة</h3><p class="muted">السؤال قبل التصميم إذا كانت المعلومات ناقصة أو غير منطقية.</p></div>
        <div class="handbook-step"><h3>التصميم</h3><p class="muted">مراعاة الهوية والواقع التنفيذي وعدم الاكتفاء بالشكل.</p></div>
        <div class="handbook-step"><h3>المراجعة</h3><p class="muted">تدقيق النصوص والقياسات والخامات قبل الإرسال.</p></div>
        <div class="handbook-step"><h3>ملفات الإنتاج</h3><p class="muted">تجهيز ملف واضح للمعمل أو الطباعة مع الملاحظات اللازمة.</p></div>
        <div class="handbook-step"><h3>المتابعة</h3><p class="muted">متابعة التعديلات أو التنفيذ عند الحاجة حتى لا يتوقف المشروع.</p></div>
    </div>
</section>

<section id="execution" class="grid two handbook-section">
    <div class="panel">
        <span class="eyebrow">يرفع التقييم</span>
        <h2>نقاط إيجابية</h2>
        <ul class="handbook-list">
            <li>التسليم في الوقت المتفق عليه.</li>
            <li>تقليل الأخطاء من أسبوع لآخر.</li>
            <li>تجهيز ملفات إنتاج أوضح وأسهل على المعمل.</li>
            <li>الاستجابة للملاحظات بجدية.</li>
        </ul>
    </div>
    <div class="panel">
        <span class="eyebrow">يخفض التقييم</span>
        <h2>نقاط سلبية</h2>
        <ul class="handbook-list">
            <li>تكرار الخطأ نفسه بعد التنبيه.</li>
            <li>إرسال تصميم غير قابل للتنفيذ.</li>
            <li>تأخير العمل دون إبلاغ مدير التصميم.</li>
            <li>رفض الملاحظات بدل تحويلها إلى تحسين.</li>
        </ul>
    </div>
</section>

<section id="evidence" class="panel handbook-section">
    <span class="eyebrow">الأدلة</span>
    <h2>ما الذي يجب توثيقه؟</h2>
    <div class="grid three">
        <div class="timeline-item"><h3>Scoreboard أسبوعي</h3><p class="muted">أمثلة مختصرة على النقاط الإيجابية والسلبية والأخطاء والمرونة.</p></div>
        <div class="timeline-item"><h3>السجلات والملاحظات</h3><p class="muted">مواقف مهمة، أخطاء إنتاجية، أو نقاط تطور تستحق الرجوع إليها.</p></div>
        <div class="timeline-item"><h3>ملفات داعمة</h3><p class="muted">صور، PDF، ملفات إنتاج، أو أي دليل يساعد الإدارة على فهم القرار.</p></div>
    </div>
</section>

<section id="raise" class="handbook-callout handbook-section">
    <h2>قرار الزيادة</h2>
    <p class="muted">
        الزيادة ليست تلقائية. يجب مراجعة خطة 3 أشهر، Scoreboard الأسبوعي، التقييم الشهري، والسجلات والملاحظات. الحد المهم في الشهر الثالث هو 85% للنظر بجدية في زيادة الراتب.
    </p>
</section>
@endsection
