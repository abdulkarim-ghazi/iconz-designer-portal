@extends('layouts.app')

@section('title', 'دليل المصممة')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">iConz Handbook</span>
        <h1>دليل تطوير المصممة داخل iConz</h1>
        <p class="muted">مرجع عملي لمدير التصميم والمدير العام لتوضيح المطلوب من المصممين ومتابعة الأداء خلال فترة التقييم.</p>
    </div>
</header>

<div class="module-tabs" style="margin-bottom:16px">
    <button type="button" class="active" data-handbook-tab="intro">البداية</button>
    <button type="button" data-handbook-tab="expectations">توقعات الشركة</button>
    <button type="button" data-handbook-tab="successful">المصممة الناجحة</button>
    <button type="button" data-handbook-tab="execution">قابلية التنفيذ</button>
    <button type="button" data-handbook-tab="workflow">خطوات العمل</button>
    <button type="button" data-handbook-tab="flexibility">المرونة</button>
    <button type="button" data-handbook-tab="positive">نقاط إيجابية</button>
    <button type="button" data-handbook-tab="negative">نقاط سلبية</button>
    <button type="button" data-handbook-tab="months">خطة 3 أشهر</button>
    <button type="button" data-handbook-tab="evaluation">التقييم والزيادة</button>
    <button type="button" data-handbook-tab="rights">الحقوق والواجبات</button>
</div>

<section class="form-pane active" data-handbook-pane="intro">
    <div class="grid two">
        <div class="panel">
            <h2>أهلاً بك في iConz</h2>
            <p class="muted">هذا الدليل يوضح طريقة العمل المتوقعة من المصممة خلال فترة التقييم، وما الذي تتم متابعته أسبوعياً وشهرياً.</p>
            <div class="module-note">هدفه ليس التعقيد، بل جعل التوقعات واضحة: ما المطلوب، ما المقبول، وما الذي يحتاج إلى تحسين.</div>
        </div>
        <div class="panel">
            <h2>الفكرة الأساسية</h2>
            <p class="muted">المصممة ليست مسؤولة فقط عن إخراج تصميم جميل، بل عن فهم طلب الزبون، مراعاة القياسات والمواد، تجهيز ملف قابل للإنتاج، والتعاون مع الفريق حتى التسليم.</p>
            <ul class="grid">
                <li class="timeline-item">تصميم جميل وواضح.</li>
                <li class="timeline-item">ملف مفهوم للمعمل أو الطباعة.</li>
                <li class="timeline-item">متابعة منطقية عند وجود تعديل أو مشكلة.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="expectations">
    <div class="panel">
        <h2>ما الذي تتوقعه الشركة من المصمم؟</h2>
        <div class="grid two">
            <ul class="grid">
                <li class="timeline-item">فهم طلب الزبون قبل البدء وعدم افتراض التفاصيل الناقصة.</li>
                <li class="timeline-item">الالتزام بالمواعيد وإبلاغ المدير عند وجود تأخير.</li>
                <li class="timeline-item">مراجعة القياسات والنصوص والشعار والألوان قبل الإرسال.</li>
                <li class="timeline-item">تجهيز ملفات إنتاج وطباعة واضحة ومفهومة.</li>
                <li class="timeline-item">التعاون مع المعمل والإنتاج والتركيب عند الحاجة.</li>
            </ul>
            <ul class="grid">
                <li class="timeline-item">تحمل مسؤولية الأخطاء الناتجة عن التصميم أو نقص المراجعة.</li>
                <li class="timeline-item">إظهار مرونة مناسبة في الحالات المستعجلة والمهمة.</li>
                <li class="timeline-item">التعلم من الأخطاء وعدم تكرارها بعد التنبيه.</li>
                <li class="timeline-item">متابعة المشروع عند الحاجة وعدم تركه بمجرد إرسال التصميم.</li>
                <li class="timeline-item">تقديم حلول عملية بدل تنفيذ الطلب حرفياً فقط.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="successful">
    <div class="panel">
        <h2>ما معنى المصممة الناجحة في iConz؟</h2>
        <div class="grid two">
            <ul class="grid">
                <li class="timeline-item">تفهم المطلوب وتسأل عندما تكون المعلومات ناقصة.</li>
                <li class="timeline-item">تفكر في قابلية التنفيذ قبل جمال الشكل فقط.</li>
                <li class="timeline-item">تعرف أن التصميم مرتبط بمواد وقياسات ووقت وتكلفة.</li>
                <li class="timeline-item">تراجع عملها قبل التسليم.</li>
            </ul>
            <ul class="grid">
                <li class="timeline-item">تجهز ملف إنتاج واضحاً عند الحاجة.</li>
                <li class="timeline-item">تتابع الملاحظات والتعديلات بدون تأخير غير مبرر.</li>
                <li class="timeline-item">تتقبل النقد وتحوّله إلى تحسين في العمل القادم.</li>
                <li class="timeline-item">تحافظ على تواصل واضح مع مدير التصميم.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="execution">
    <div class="grid two">
        <div class="panel">
            <h2>ماذا يعني أن التصميم قابل للتنفيذ؟</h2>
            <p class="muted">التصميم القابل للتنفيذ يراعي الواقع: المواد، القياسات، السماكات، طريقة التثبيت، الإضاءة، الطباعة، التكلفة، وموعد التسليم.</p>
            <ul class="grid">
                <li class="timeline-item">لا يبدأ التصميم قبل وضوح القياسات الأساسية.</li>
                <li class="timeline-item">لا تقدم فكرة جميلة إذا كانت غير قابلة للتصنيع.</li>
                <li class="timeline-item">تذكر ملاحظات الإنتاج بوضوح داخل الملف.</li>
                <li class="timeline-item">تراجع النسخة النهائية المعتمدة قبل إرسالها.</li>
            </ul>
        </div>
        <div class="panel">
            <h2>أسئلة قبل اعتماد الفكرة</h2>
            <ul class="grid">
                <li class="timeline-item">هل يمكن تصنيعها بالمواد المتاحة؟</li>
                <li class="timeline-item">هل المقاسات واضحة ومناسبة للموقع؟</li>
                <li class="timeline-item">هل النصوص والشعارات قابلة للقراءة؟</li>
                <li class="timeline-item">هل الملف النهائي مفهوم لمن سينفذه؟</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="workflow">
    <div class="panel">
        <h2>خطوات العمل الصحيحة</h2>
        <div class="grid three">
            <div class="timeline-item"><span class="badge gold">1</span><h3>استلام المشروع</h3><p class="muted">قراءة الطلب، الزبون، المكان، المقاسات، الموعد، والمواد.</p></div>
            <div class="timeline-item"><span class="badge gold">2</span><h3>طرح الأسئلة</h3><p class="muted">السؤال قبل التصميم إذا كانت المعلومات ناقصة أو غير منطقية.</p></div>
            <div class="timeline-item"><span class="badge gold">3</span><h3>أثناء التصميم</h3><p class="muted">مراعاة الهوية والواقع التنفيذي وعدم الاكتفاء بالشكل.</p></div>
            <div class="timeline-item"><span class="badge gold">4</span><h3>قبل الإرسال</h3><p class="muted">تدقيق النصوص والقياسات والشعار والألوان والخامات.</p></div>
            <div class="timeline-item"><span class="badge gold">5</span><h3>ملفات الإنتاج</h3><p class="muted">تجهيز ملف واضح للمعمل أو الطباعة مع الملاحظات اللازمة.</p></div>
            <div class="timeline-item"><span class="badge gold">6</span><h3>بعد التسليم</h3><p class="muted">متابعة التعديلات أو التنفيذ عند الحاجة حتى لا يتوقف المشروع.</p></div>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="flexibility">
    <div class="grid two">
        <div class="panel">
            <h2>ما المقصود بالمرونة؟</h2>
            <p class="muted">المرونة لا تعني العمل طوال الوقت، بل تعني الاستجابة المناسبة عند وجود أمر مؤثر على زبون أو إنتاج أو تركيب أو موعد تسليم.</p>
            <ul class="grid">
                <li class="timeline-item">الرد عند وجود أمر مهم أو مستعجل.</li>
                <li class="timeline-item">إعطاء تحديث واضح إذا لم يمكن العمل فوراً.</li>
                <li class="timeline-item">التعاون مع الفريق عند ضغط المشاريع.</li>
                <li class="timeline-item">المساعدة في حل مشكلة مرتبطة بالتصميم أو الملف.</li>
            </ul>
        </div>
        <div class="panel">
            <h2>المرونة لا تعني</h2>
            <ul class="grid">
                <li class="timeline-item">قبول ضغط غير منظم دون توضيح الأولويات.</li>
                <li class="timeline-item">العمل على تعديلات غير واضحة بدون سؤال.</li>
                <li class="timeline-item">تجاوز المدير أو تسليم ملفات غير معتمدة.</li>
                <li class="timeline-item">تجاهل الوقت أو جودة الملف بحجة الاستعجال.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="positive">
    <div class="panel">
        <h2>كيف يحصل المصمم على نقاط إيجابية؟</h2>
        <div class="grid two">
            <ul class="grid">
                <li class="timeline-item">التسليم في الوقت المتفق عليه.</li>
                <li class="timeline-item">تقليل الأخطاء من أسبوع لآخر.</li>
                <li class="timeline-item">مراجعة العمل قبل إرساله.</li>
                <li class="timeline-item">طرح أسئلة صحيحة قبل البدء.</li>
            </ul>
            <ul class="grid">
                <li class="timeline-item">تجهيز ملفات إنتاج أوضح.</li>
                <li class="timeline-item">التعاون مع المعمل أو الأقسام عند الحاجة.</li>
                <li class="timeline-item">الاستجابة للملاحظات بجدية.</li>
                <li class="timeline-item">تقديم حل عند ظهور مشكلة بدلاً من انتظار التعليمات فقط.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="negative">
    <div class="panel">
        <h2>ما الذي يؤثر سلباً على التقييم؟</h2>
        <div class="grid two">
            <ul class="grid">
                <li class="timeline-item">عدم مراجعة القياسات أو النصوص أو الشعار.</li>
                <li class="timeline-item">إرسال تصميم غير قابل للتنفيذ.</li>
                <li class="timeline-item">تجهيز ملف إنتاج ناقص أو غير مفهوم.</li>
                <li class="timeline-item">التأخير دون إبلاغ مدير التصميم.</li>
            </ul>
            <ul class="grid">
                <li class="timeline-item">عدم الرد في موقف مهم أو مستعجل.</li>
                <li class="timeline-item">تكرار نفس الخطأ بعد التنبيه.</li>
                <li class="timeline-item">تحميل الآخرين مسؤولية أخطاء ناتجة عن التصميم أو نقص المراجعة.</li>
                <li class="timeline-item">رفض الملاحظات بدل التعامل معها كفرصة تحسين.</li>
            </ul>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="months">
    <div class="grid three">
        <div class="panel">
            <span class="badge gold">الشهر الأول</span>
            <h2>فهم النظام</h2>
            <p class="muted">التعرف على طريقة استلام المشاريع، الأسئلة المطلوبة، أساسيات ملفات الإنتاج، وتقبل الملاحظات.</p>
        </div>
        <div class="panel">
            <span class="badge gold">الشهر الثاني</span>
            <h2>تقليل الأخطاء وتحسين الجودة</h2>
            <p class="muted">تحسين جودة التصميم، تقليل التعديلات الناتجة عن سوء الفهم، وتجهيز ملفات أوضح للمعمل.</p>
        </div>
        <div class="panel">
            <span class="badge gold">الشهر الثالث</span>
            <h2>الاعتماد والاستقلالية</h2>
            <p class="muted">إدارة المهام بدرجة أعلى من الاعتماد، متابعة المشروع حتى التنفيذ، وإظهار مرونة مع الضغط.</p>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="evaluation">
    <div class="panel">
        <h2>كيف يتم التقييم؟</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>المحور</th><th>الوزن</th></tr></thead>
                <tbody>
                    <tr><td>جودة التصميم والإبداع</td><td>15%</td></tr>
                    <tr><td>دقة التفاصيل</td><td>10%</td></tr>
                    <tr><td>قابلية التصميم للتنفيذ</td><td>15%</td></tr>
                    <tr><td>سرعة الإنجاز والالتزام بالمواعيد</td><td>10%</td></tr>
                    <tr><td>فهم متطلبات المشروع</td><td>10%</td></tr>
                    <tr><td>ملفات الإنتاج والطباعة</td><td>15%</td></tr>
                    <tr><td>المتابعة مع المعمل أو الموقع</td><td>10%</td></tr>
                    <tr><td>التعاون مع الأقسام</td><td>5%</td></tr>
                    <tr><td>المرونة والاستجابة</td><td>10%</td></tr>
                </tbody>
            </table>
        </div>
        <div class="grid two" style="margin-top:14px">
            <div class="module-note">التقييم يعتمد على الأداء المسجل، المتابعة الأسبوعية، ملاحظات الأداء، الملفات، والأمثلة العملية.</div>
            <div class="module-note">زيادة الراتب بعد فترة التقييم ليست تلقائية، بل ترتبط بالتطور، قلة الأخطاء، والقدرة على العمل باستقلالية.</div>
        </div>
    </div>
</section>

<section class="form-pane" data-handbook-pane="rights">
    <div class="grid two">
        <div class="panel">
            <h2>حقوق المصمم خلال فترة التقييم</h2>
            <ul class="grid">
                <li class="timeline-item">معرفة المطلوب بوضوح.</li>
                <li class="timeline-item">الحصول على ملاحظات تساعده على التطور.</li>
                <li class="timeline-item">التقييم بعدالة وعدم الحكم من موقف واحد فقط.</li>
                <li class="timeline-item">توضيح سبب أي ملاحظة أو تقييم سلبي.</li>
                <li class="timeline-item">الحصول على فرصة حقيقية لتحسين الأداء.</li>
            </ul>
        </div>
        <div class="panel">
            <h2>واجبات المصمم خلال فترة التقييم</h2>
            <ul class="grid">
                <li class="timeline-item">الالتزام بالدوام والتعليمات.</li>
                <li class="timeline-item">احترام مواعيد التسليم.</li>
                <li class="timeline-item">مراجعة العمل قبل تسليمه.</li>
                <li class="timeline-item">السؤال عند نقص المعلومات.</li>
                <li class="timeline-item">تقبل الملاحظات وعدم تكرار نفس الخطأ.</li>
            </ul>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const handbookTabs = document.querySelectorAll('[data-handbook-tab]');
    const handbookPanes = document.querySelectorAll('[data-handbook-pane]');

    document.addEventListener('click', event => {
        const tab = event.target.closest('[data-handbook-tab]');
        if (!tab) return;

        handbookTabs.forEach(item => item.classList.toggle('active', item === tab));
        handbookPanes.forEach(pane => {
            pane.classList.toggle('active', pane.dataset.handbookPane === tab.dataset.handbookTab);
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endpush
