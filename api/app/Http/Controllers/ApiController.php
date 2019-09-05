<?php
namespace App\Http\Controllers;

use App\Contracts\ApiResource;
use App\Enumerations\ApiOperation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all of a resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function all(Request $request): AnonymousResourceCollection
    {
        $resourceKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize(ApiOperation::ALL()->getValue() . '-' . $resourceKey);

        $resource = resolve($resourceKey);

        return $resource->getResourceClass()::collection($resource::all());
    }

    /**
     * Get one of a resource.
     *
     * @param Request $request
     * @param ApiResource $resource
     * @return Resource
     */
    public function one(Request $request, ApiResource $resource): Resource
    {
        $modelKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize('one-' . $modelKey);

        $resourceClass = $resource::getResourceClass();

        return $resourceClass::make($resource);
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
