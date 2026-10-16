<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request->validated());

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->authenticated($request);
        }

        return back()->withErrors([
            'email_or_phone' => __('auth.invalid_credentials'),
        ])->withInput($request->only('email_or_phone'));
    }

    protected function getCredentials(array $validated): array
    {
        $field = filter_var($validated['email_or_phone'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return [
            $field => $validated['email_or_phone'],
            'password' => $validated['password'],
        ];
    }

    protected function authenticated($request)
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === UserRole::ADMIN->value || $role === UserRole::SUPER_ADMIN->value) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
