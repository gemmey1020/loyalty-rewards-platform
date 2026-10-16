<?php

namespace App\Http\Controllers;

use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Http\Requests\Password\UpdatePasswordRequest;
use App\Models\RecoveryToken;
use App\Models\SystemSetup;
use App\Services\Services\PasswordResetService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    protected $passwordResetService;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService;
    }

    public function showForgotForm()
    {
        $image = SystemSetup::getDefaultImage('login');

        return view('auth.forgot-password', compact('image'));
    }

    public function sendResetLink(ForgetPasswordRequest $request)
    {
        try {
            $this->passwordResetService->handleReset($request->validated()['email']);

            return redirect()->route('get.password.forget')->with('success', __('auth.forgot_password.password_reset_email_sent'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function showResetForm($token)
    {
        $recoveryToken = RecoveryToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if (! $recoveryToken) {
            return redirect()->route('login')->with(['error' => __('auth.forgot_password.invalid_or_expired_token')]);
        }

        $image = SystemSetup::getDefaultImage('login');

        return view('auth.reset-password', compact('token', 'image'));
    }

    public function resetPassword(UpdatePasswordRequest $request)
    {
        $recoveryToken = RecoveryToken::where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if (! $recoveryToken) {
            return redirect()->route('login')->with(['error' => __('auth.forgot_password.invalid_or_expired_token')]);
        }

        // Update password
        $user = $recoveryToken->user;
        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate token
        $recoveryToken->used = true;
        $recoveryToken->save();

        return redirect()->route('login')->with('success', __('auth.reset_password.password_reset_success'));
    }
}
