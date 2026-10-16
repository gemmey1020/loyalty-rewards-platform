<?php

namespace App\Enums;

/**
 * @method static static USER()
 * @method static static ADMIN()
 * @method static static SUPER_ADMIN()
 */
enum UserRole: int
{
    case USER = 0;
    case ADMIN = 1;
    case SUPER_ADMIN = 2;
}
