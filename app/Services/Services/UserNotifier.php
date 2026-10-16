<?php

namespace App\Services\Services;

use App\Mail\ActivationEmail;
use App\Models\AccountActivation;
use App\Models\User;
use App\Services\Contracts\UserNotifierInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserNotifier implements UserNotifierInterface
{
    public function sendActivationEmail(User $user): string
    {
        $existingToken = AccountActivation::where('user_id', $user->id)->first();

        if ($existingToken && $existingToken->expires_at > Carbon::now()) {
            return 'already_sent';
        }

        $token = Str::random(64);

        AccountActivation::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        Mail::to($user->email)->send(new ActivationEmail($user, $token));

        return 'sent';
    }
}
