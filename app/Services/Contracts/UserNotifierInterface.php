<?php

namespace App\Services\Contracts;

use App\Models\User;

interface UserNotifierInterface
{
    public function sendActivationEmail(User $user): string;
}
