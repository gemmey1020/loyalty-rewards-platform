<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccountActivation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivationController extends Controller
{
    public function __invoke($token)
    {
        $activation = AccountActivation::where('token', $token)->first();

        if (! $activation || Carbon::now()->greaterThan($activation->expires_at)) {
            return response()->json(['message' => 'Invalid or expired activation token.'], 400);
        }

        $user = $activation->user;

        Auth::login($user);

        $user->email_verified_at = now();
        $user->save();

        $activation->delete();

        return redirect()->route('user.dashboard');
    }
}
