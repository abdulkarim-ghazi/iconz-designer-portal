@extends('layouts.app')

@section('title', $meta['title'].' - '.$record->employee_name)

@php
    $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    $monthLabels = ['month1' => 'الشهر الأول', 'month2' => 'الشهر الثاني', 'month3' => 'الشهر الثالث'];
    $activeEvaluationMonth = old('active_month', $record->current_month ?: 'month1');
    $weeklyByLabel = $record->weeklyEntries->keyBy('week_label');
    $monthlyByKey = $record->monthlyEvaluations->keyBy('month_key');
    $evaluationRows = [
        'quality_score' => ['label' => 'جودة التصميم والإبداع', 'weight' => 15],
        'details_score' => ['label' => 'دقة التفاصيل', 'weight' => 10],
        'execution_score' => ['label' => 'قابلية التصميم للتنفيذ', 'weight' => 15],
        'speed_score' => ['label' => 'سرعة الإنجاز والالتزام', 'weight' => 10],
        'brief_score' => ['label' => 'فهم طلب الزبون', 'weight' => 10],
        'production_score' => ['label' => 'ملفات الإنتاج والطباعة', 'weight' => 15],
        'followup_score' => ['label' => 'المتابعة مع المعمل أو الموقع', 'weight' => 10],
        'teamwork_score' => ['label' => 'التعاون مع الأقسام', 'weight' => 5],
        'flexibility_score' => ['label' => 'المرونة والاستجابة', 'weight' => 10],
    ];
    $canManage = auth()->user()->canManageDesignerData();
@endphp

@section('content')
<header class="topbar">
    <div>
        <span class="eyebrow">{{ $meta['owner'] }}</span>
        <h1>{{ $meta['title'] }}</h1>
        <p class="muted">{{ $record->employee_name }} · {{ $record->customer_name ?: 'بدون زبون' }} · {{ $record->project_name ?: 'بدون مشروع' }}</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('modules.index', $module) }}">رجوع للموديول</a>
        <a class="btn" href="{{ route('records.show', $record) }}">السجل الكامل</a>
    </div>
</header>

<form method="POST" action="{{ route('modules.update', [$module, $record]) }}" class="grid" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @unless($canManage)
        <div class="module-note">أنت الآن في وضع مراقبة. الأدمن يراجع البيانات، ومدير التصميم فقط يستطيع التعديل أو رفع ملفات داعمة.</div>
    @endunless

    <fieldset @disabled(! $canManage) class="grid" style="border:0;padding:0;margin:0">
        @if($module === 'designer-data')
            <section class="panel">
                <h2>بيانات المصمم</h2>
                <p class="muted">هذا القسم يضعه مدير التصميم، وأي تغيير يظهر داخل السجل الكامل وسجل التغييرات.</p>
                <div class="form-grid">
                    <div class="field">
                        <label>المصمم</label>
                        <select name="designer_id" required>
                            @foreach($designers as $designer)
                                <option value="{{ $designer->id }}" @selected(old('designer_id', $record->designer_id) == $designer->id)>{{ $designer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>اسم الموظفة الظاهر</label><input name="employee_name" value="{{ old('employee_name', $record->employee_name) }}"></div>
                    <div class="field"><label>المسمى الوظيفي</label><input name="job_title" value="{{ old('job_title', $record->job_title) }}" required></div>
                    <div class="field"><label>تاريخ المباشرة</label><input type="date" name="start_date" value="{{ old('start_date', optional($record->start_date)->format('Y-m-d')) }}"></div>
                    <div class="field"><label>المدير المباشر</label><input name="manager_name" value="{{ old('manager_name', $record->manager_name) }}"></div>
                    <div class="field"><label>مدة التجربة</label><input name="trial_period" value="{{ old('trial_period', $record->trial_period) }}" required></div>
                    <div class="field"><label>مرحلة التقييم الحالية</label><select name="current_month">@foreach($monthLabels as $key => $label)<option value="{{ $key }}" @selected(old('current_month', $record->current_month) === $key)>{{ $label }}</option>@endforeach</select></div>
                    <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
                    <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}"></div>
                </div>
            </section>
        @endif

        @if($module === 'weekly-followup')
            <section class="panel">
                <h2>متابعة المصمم الأسبوعية</h2>
                <p class="muted">يضعها مدير التصميم لمصمم معين، وترتبط بنفس سجل التقييم والزبون أو المشروع.</p>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>الأسبوع</th><th>المشروع</th><th>نقطة إيجابية</th><th>نقطة سلبية</th><th>مرونة</th><th>خطأ إنتاجي</th><th>ملاحظة مدير التصميم</th></tr></thead>
                        <tbody>
                            @foreach($weeks as $i => $week)
                                @php($row = $weeklyByLabel->get($week))
                                <tr>
                                    <td><input type="hidden" name="weekly[{{ $i }}][week_label]" value="{{ $week }}"><strong>{{ $week }}</strong></td>
                                    <td><input name="weekly[{{ $i }}][project]" value="{{ $row?->project }}"></td>
                                    <td><textarea name="weekly[{{ $i }}][positive]">{{ $row?->positive }}</textarea></td>
                                    <td><textarea name="weekly[{{ $i }}][negative]">{{ $row?->negative }}</textarea></td>
                                    <td><select name="weekly[{{ $i }}][flexibility]"><option></option><option @selected($row?->flexibility==='نعم')>نعم</option><option @selected($row?->flexibility==='جزئياً')>جزئياً</option><option @selected($row?->flexibility==='لا')>لا</option></select></td>
                                    <td><input type="checkbox" name="weekly[{{ $i }}][production_error]" value="1" @checked($row?->production_error)></td>
                                    <td><textarea name="weekly[{{ $i }}][manager_note]">{{ $row?->manager_note }}</textarea></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        @if($module === 'performance-notes')
            <section class="panel">
                <h2>ملاحظات الأداء والعمل</h2>
                <p class="muted">ملاحظات لمصمم معين: نقطة إيجابية، خطأ إنتاجي، أو ملاحظة عامة مرتبطة بالمشروع.</p>
                @for($i = 0; $i < 5; $i++)
                    @php($log = $record->activityLogs[$i] ?? null)
                    <div class="form-grid" style="margin-bottom:12px">
                        <input type="hidden" name="logs[{{ $i }}][id]" value="{{ $log?->id }}">
                        <div class="field"><label>النوع</label><select name="logs[{{ $i }}][type]"><option>ملاحظة</option><option @selected($log?->type==='نقطة إيجابية')>نقطة إيجابية</option><option @selected($log?->type==='خطأ إنتاجي')>خطأ إنتاجي</option></select></div>
                        <div class="field"><label>المشروع</label><input name="logs[{{ $i }}][project]" value="{{ $log?->project }}"></div>
                        <div class="field"><label>التأثير</label><input name="logs[{{ $i }}][impact]" value="{{ $log?->impact }}"></div>
                        <div class="field"><label>التاريخ</label><input type="date" name="logs[{{ $i }}][logged_at]" value="{{ optional($log?->logged_at)->format('Y-m-d') }}"></div>
                        <div class="field full"><label>الملاحظة</label><textarea name="logs[{{ $i }}][note]">{{ $log?->note }}</textarea></div>
                    </div>
                @endfor
            </section>
        @endif

        @if($module === 'monthly-evaluation')
            <section class="panel">
                <h2>التقييم الشهري</h2>
                <p class="muted">يضعه مدير التصميم لمصمم معين. كل شهر محفوظ وحده، والنتيجة من 100 حسب الأوزان المعتمدة.</p>
                <div class="module-tabs" data-month-tabs>
                    @foreach($monthLabels as $monthKey => $monthLabel)
                        <a href="#{{ $monthKey }}" class="{{ $activeEvaluationMonth === $monthKey ? 'active' : '' }}" data-month-tab="{{ $monthKey }}">{{ $monthLabel }}</a>
                    @endforeach
                </div>
                @foreach($monthLabels as $monthKey => $monthLabel)
                    @php($evaluation = $monthlyByKey->get($monthKey))
                    <div class="form-pane {{ $activeEvaluationMonth === $monthKey ? 'active' : '' }}" data-month-pane="{{ $monthKey }}">
                    <div class="timeline-item" style="margin-top:14px">
                        <div class="topbar" style="margin-bottom:10px">
                            <div>
                                <span class="eyebrow">{{ $monthLabel }}</span>
                                <h2>نتيجة {{ $monthLabel }}</h2>
                            </div>
                            <span class="badge green">{{ $evaluation?->total_score ?: 0 }} / 100</span>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead><tr><th>المحور</th><th>الوزن</th><th>النقطة</th><th>ملاحظة</th></tr></thead>
                                <tbody>
                                    @foreach($evaluationRows as $field => $row)
                                        <tr>
                                            <td>{{ $row['label'] }}</td>
                                            <td>/{{ $row['weight'] }}</td>
                                            <td><input type="number" min="0" max="{{ $row['weight'] }}" name="evaluations[{{ $monthKey }}][{{ $field }}]" value="{{ old('evaluations.'.$monthKey.'.'.$field, $evaluation?->{$field} ?? 0) }}"></td>
                                            <td><input name="evaluations[{{ $monthKey }}][notes][{{ $field }}]" value="{{ old('evaluations.'.$monthKey.'.notes.'.$field, $evaluation?->notes[$field] ?? '') }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="field full" style="margin-top:12px">
                            <label>خلاصة مدير التصميم / {{ $monthLabel }}</label>
                            <textarea name="evaluations[{{ $monthKey }}][manager_answers][summary]">{{ old('evaluations.'.$monthKey.'.manager_answers.summary', $evaluation?->manager_answers['summary'] ?? '') }}</textarea>
                        </div>
                    </div>
                    </div>
                @endforeach
            </section>
        @endif

        @if($module === 'management-decision')
            <section class="panel">
                <h2>قرار الإدارة والزيادة</h2>
                <p class="muted">هذا القسم خاص بخلاصة الإدارة بعد مراجعة المتابعة الأسبوعية، ملاحظات الأداء، والتقييم الشهري.</p>
                <div class="form-grid">
                    <div class="field"><label>الراتب الحالي</label><input type="number" step="0.01" name="current_salary" value="{{ old('current_salary', $record->current_salary) }}"></div>
                    <div class="field"><label>الزيادة المقترحة</label><input name="proposed_raise" value="{{ old('proposed_raise', $record->proposed_raise) }}" placeholder="مثال: 100,000 أو 10%"></div>
                    <div class="field"><label>القرار النهائي</label><select name="final_decision">
                        @foreach(['لم يتم اتخاذ القرار','تثبيت المصممة','تمديد فترة المتابعة','زيادة مقترحة','لا توجد زيادة حالياً','إنهاء التجربة'] as $decision)
                            <option value="{{ $decision }}" @selected(old('final_decision', $record->final_decision ?: 'لم يتم اتخاذ القرار') === $decision)>{{ $decision }}</option>
                        @endforeach
                    </select></div>
                    <div class="field"><label>تاريخ القرار</label><input type="date" name="decision_date" value="{{ old('decision_date', optional($record->decision_date)->format('Y-m-d')) }}"></div>
                    <div class="field full"><label>سبب القرار</label><textarea name="decision_reason">{{ old('decision_reason', $record->decision_reason) }}</textarea></div>
                    <div class="field full"><label>الخطة القادمة</label><textarea name="next_plan">{{ old('next_plan', $record->next_plan) }}</textarea></div>
                </div>
            </section>
        @endif

        @if(in_array($module, ['weekly-followup', 'performance-notes', 'monthly-evaluation', 'management-decision'], true))
            <section class="panel">
                <h2>ملفات أو صور داعمة</h2>
                <p class="muted">ارفع صوراً أو PDF أو ملفات مرتبطة بهذه المتابعة أو الملاحظة. ستظهر داخل السجل الكامل للمصمم.</p>
                <div class="form-grid">
                    <div class="field"><label>عنوان الملفات</label><input name="support_title" placeholder="مثال: صورة خطأ، ملف موافقة، لقطة متابعة"></div>
                    <div class="field"><label>اختيار الملفات</label><input type="file" name="support_files[]" multiple></div>
                </div>
            </section>
        @endif
    </fieldset>

    <div class="actions">
        @if($canManage)
            <button class="btn primary" type="submit">حفظ {{ $meta['title'] }}</button>
        @endif
        <a class="btn" href="{{ route('modules.index', $module) }}">رجوع</a>
    </div>
</form>
@endsection

@push('scripts')
@if($module === 'monthly-evaluation')
<script>
    document.addEventListener('click', event => {
        const tab = event.target.closest('[data-month-tab]');
        if (!tab) return;

        event.preventDefault();
        const selected = tab.dataset.monthTab;
        document.querySelectorAll('[data-month-tab]').forEach(item => item.classList.toggle('active', item === tab));
        document.querySelectorAll('[data-month-pane]').forEach(pane => {
            pane.classList.toggle('active', pane.dataset.monthPane === selected);
        });
    });
</script>
@endif
@endpush
