<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Contracts\UserNotifierInterface;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private UserNotifierInterface $notifier;

    public function __construct(UserNotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => UserRole::USER->value,
            'birth_date' => $validated['date'] ?? null,
        ]);

         $status = $this->notifier->sendActivationEmail($user);

         return redirect()->route('login')->with(
             $status === 'sent' ? 'success' : 'error',
             $status === 'sent'
                 ? __('message.activation_email_sent')
                 : __('message.activation_email_already_sent')
         );
    }
}
