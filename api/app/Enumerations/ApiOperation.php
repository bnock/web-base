<?php
namespace App\Enumerations;

use MyCLabs\Enum\Enum;

/**
 * @method static ApiOperation ALL()
 * @method static ApiOperation ONE()
 * @method static ApiOperation CREATE()
 * @method static ApiOperation UPDATE()
 * @method static ApiOperation DELETE()
 */
class ApiOperation extends Enum
{
    private const ALL = 'all';
    private const ONE = 'one';
    private const CREATE = 'create';
    private const UPDATE = 'update';
    private const DELETE = 'delete';
}
