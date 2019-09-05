<?php
namespace app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

abstract class AbstractResource extends JsonResource
{
    /**
     * Get the array representation of this resource.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $out  = [];

        foreach (get_object_vars($this->resource) as $name => $value) {
            $out[Str::camel($name)] = $value;
        }

        return $out;
    }
}
