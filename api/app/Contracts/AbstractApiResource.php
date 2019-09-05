<?php
namespace App\Contracts;

use App\Http\Resources\AbstractResource;
use App\Policies\AbstractPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class AbstractApiResource extends Model implements ApiResource
{
    public static function getPolicyClass(): string
    {
        return AbstractPolicy::class;
    }

    public static function getResourceClass(): string
    {
        return AbstractResource::class;
    }

    public static function getResourceName(): string
    {
        return Str::camel(Str::singular(class_basename(self::class)));
    }
}
