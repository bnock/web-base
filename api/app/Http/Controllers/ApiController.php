<?php
namespace App\Http\Controllers;

use App\Contracts\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function all(Request $request): AnonymousResourceCollection
    {
        $model = resolve(Str::replaceFirst('api/', '', $request->route()->uri()));

        return $model->getResourceClass()::collection($model::all());
    }

    public function one(ApiResource $resource): Resource
    {

    }

    public function create(Request $request): Resource
    {

    }

    public function update(Request $request, ApiResource $resource): Resource
    {

    }

    public function delete(Request $request, ApiResource $resource): Resource
    {

    }
}
