<?php
namespace App\Contracts;

use App\Enumerations\Role;
use App\Http\Resources\AbstractResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class AbstractApiResource extends Model implements ApiResource
{
    public static function getResourceClass(): string
    {
        return AbstractResource::class;
    }

    public static function getResourceKey(): string
    {
        return Str::camel(class_basename(static::class));
    }

    public static function getAllRoleOrGate()
    {
        return Role::ADMIN()->getValue();
    }

    public static function getOneRoleOrGate()
    {
        return Role::ADMIN()->getValue();
    }

    public static function getCreateRoleOrGate()
    {
        return Role::ADMIN()->getValue();
    }

    public static function getUpdateRoleOrGate()
    {
        return Role::ADMIN()->getValue();
    }

    public static function getDeleteRoleOrGate()
    {
        return Role::ADMIN()->getValue();
    }
}
