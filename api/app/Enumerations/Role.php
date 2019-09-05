<?php

namespace App\Enumerations;

use MyCLabs\Enum\Enum;

/**
 * @method static Role ADMIN()
 * @method static Role USER()
 */
class Role extends Enum
{
    private const ADMIN = 'admin';
    private const USER = 'user';
}
