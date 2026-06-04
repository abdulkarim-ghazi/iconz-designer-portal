@extends('layouts.app')

@section('title', 'خطة 3 أشهر')

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">خطة 3 أشهر</span>
        <h1>خطة 3 أشهر</h1>
        <p class="muted">
            النظام يجمع بين خطة العمل، التوثيق الأسبوعي، التقييم الشهري، وسجل الأدلة حتى يكون قرار الاستمرار أو زيادة الراتب مبنياً على نتائج واضحة وليس على الانطباع.
        </p>
    </div>
</header>

<section class="panel">
    <div class="module-tabs" data-plan-tabs>
        <a href="#month1Plan" class="active" data-plan-tab="month1Plan">الشهر الأول</a>
        <a href="#month2Plan" data-plan-tab="month2Plan">الشهر الثاني</a>
        <a href="#month3Plan" data-plan-tab="month3Plan">الشهر الثالث</a>
    </div>

    <div id="month1Plan" class="form-pane active" data-plan-pane="month1Plan">
        <div class="plan-panel">
            <h2>الشهر الأول: الفهم والالتزام بالنظام</h2>
            <div class="grid three">
                <div class="timeline-item">
                    <h3>فهم آلية الشركة</h3>
                    <p class="muted">استلام المشروع، قراءة القياسات، معرفة المسؤوليات، وعدم البدء قبل وضوح المعلومات.</p>
                </div>
                <div class="timeline-item">
                    <h3>جودة التصميم الأولية</h3>
                    <p class="muted">أفكار مناسبة وقابلة للتنفيذ مع مراجعة داخلية قبل الإرسال.</p>
                </div>
                <div class="timeline-item">
                    <h3>تعلم ملفات الإنتاج</h3>
                    <p class="muted">التمييز بين ملف العرض، الإنتاج، والطباعة وتجهيز ملفات بسيطة تحت إشراف المدير.</p>
                </div>
            </div>
            <div class="module-note" style="margin-top:14px">الحد الأدنى المقبول: 70%. النتيجة الجيدة: 80% وما فوق.</div>
        </div>
    </div>

    <div id="month2Plan" class="form-pane" data-plan-pane="month2Plan">
        <div class="plan-panel">
            <h2>الشهر الثاني: تحسين الجودة وتقليل الأخطاء</h2>
            <div class="grid three">
                <div class="timeline-item">
                    <h3>تطوير جودة التصميم</h3>
                    <p class="muted">تصاميم أكثر نضجاً ومرتبطة بهوية العميل والمواد الحقيقية.</p>
                </div>
                <div class="timeline-item">
                    <h3>تقليل التعديلات</h3>
                    <p class="muted">طرح أسئلة صحيحة قبل البدء وتقليل سوء الفهم والاستعجال.</p>
                </div>
                <div class="timeline-item">
                    <h3>ملفات إنتاج أوضح</h3>
                    <p class="muted">توضيح المقاسات والمواد والقص والطباعة والإضاءة والتشطيب.</p>
                </div>
            </div>
            <div class="module-note" style="margin-top:14px">الحد الأدنى المطلوب: 78%. النتيجة الجيدة: 82% وما فوق.</div>
        </div>
    </div>

    <div id="month3Plan" class="form-pane" data-plan-pane="month3Plan">
        <div class="plan-panel">
            <h2>الشهر الثالث: الاستقلالية وتحمل المسؤولية</h2>
            <div class="grid three">
                <div class="timeline-item">
                    <h3>عمل باستقلالية</h3>
                    <p class="muted">استلام المشروع وفهمه وطلب الناقص واتخاذ القرار المناسب.</p>
                </div>
                <div class="timeline-item">
                    <h3>ملفات إنتاج مكتملة</h3>
                    <p class="muted">ملفات واضحة ومنظمة تقلل أسئلة المعمل وتصلح للتنفيذ النهائي.</p>
                </div>
                <div class="timeline-item">
                    <h3>متابعة ميدانية</h3>
                    <p class="muted">متابعة المعمل أو الموقع والتدخل لحل المشاكل الناتجة عن التصميم أو سوء الفهم.</p>
                </div>
            </div>
            <div class="module-note" style="margin-top:14px">الحد الأدنى للنظر في زيادة الراتب: 85%.</div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('click', event => {
        const tab = event.target.closest('[data-plan-tab]');
        if (!tab) return;

        event.preventDefault();
        const selected = tab.dataset.planTab;
        document.querySelectorAll('[data-plan-tab]').forEach(item => item.classList.toggle('active', item === tab));
        document.querySelectorAll('[data-plan-pane]').forEach(pane => {
            pane.classList.toggle('active', pane.dataset.planPane === selected);
        });
    });
</script>
@endpush
