<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Designer::query()
            ->withCount(['records'])
            ->latest();

        if ($request->user()->isDesigner()) {
            $query->whereKey($request->user()->designer_id);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        return view('designers.index', [
            'designers' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('designers.form', [
            'designer' => new Designer([
                'job_title' => 'مصممة',
                'trial_period' => '3 أشهر',
                'current_month' => 'month1',
                'status' => 'active',
            ]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $designer = Designer::create($this->validated($request));

        return redirect()
            ->route('designers.show', $designer)
            ->with('status', 'تم إنشاء بطاقة الموظفة. يمكن الآن إضافة المتابعة الأسبوعية أو التقييم الشهري لها.');
    }

    public function show(Request $request, Designer $designer): View
    {
        abort_unless($request->user()->canViewDesigner($designer), 403);

        $designer->load(['records.monthlyEvaluations']);
        $designer->loadCount(['records']);

        return view('designers.show', ['designer' => $designer]);
    }

    public function edit(Designer $designer): View
    {
        return view('designers.form', [
            'designer' => $designer,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Designer $designer): RedirectResponse
    {
        $designer->update($this->validated($request, $designer));

        return redirect()
            ->route('designers.show', $designer)
            ->with('status', 'تم تحديث بطاقة الموظفة.');
    }

    public function destroy(Designer $designer): RedirectResponse
    {
        abort_if($designer->records()->exists(), 422, 'لا يمكن حذف موظفة لديها نشاط مرتبط.');
        $designer->delete();

        return redirect()->route('designers.index')->with('status', 'تم حذف بطاقة الموظفة.');
    }

    private function validated(Request $request, ?Designer $designer = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'job_title' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'direct_manager' => ['nullable', 'string', 'max:255'],
            'trial_period' => ['required', 'string', 'max:255'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'proposed_raise' => ['nullable', 'string', 'max:255'],
            'current_month' => ['required', 'in:month1,month2,month3'],
            'status' => ['required', 'in:active,on_hold,completed,inactive'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
