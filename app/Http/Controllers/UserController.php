<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('users.form', ['user' => new User(['role' => 'designer', 'is_active' => true]), 'mode' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        User::create($data);

        return redirect()->route('users.index')->with('status', 'تم إنشاء المستخدم.');
    }

    public function edit(User $user): View
    {
        return view('users.form', ['user' => $user, 'mode' => 'edit']);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('users.index')->with('status', 'تم تحديث المستخدم.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(auth()->id() === $user->id, 422, 'لا يمكن حذف حسابك الحالي.');
        $user->delete();

        return redirect()->route('users.index')->with('status', 'تم حذف المستخدم.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,designer'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
