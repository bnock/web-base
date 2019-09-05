<?php
namespace App\Http\Controllers;

use App\Contracts\ApiResource;
use App\Enumerations\ApiOperation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        /** @var ApiResource $apiResource */
        $apiResource = resolve($resourceKey);

        /** @var JsonResource $resourceClass */
        $resourceClass = $apiResource::getResourceClass();

        return $resourceClass::collection($apiResource::all());
    }

    /**
     * Get one of a resource.
     *
     * @param Request $request
     * @param ApiResource $apiResource
     * @return Resource
     */
    public function one(Request $request, ApiResource $apiResource): JsonResource
    {
        $resourceKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize(ApiOperation::ONE()->getValue() . '-' . $resourceKey);

        /** @var JsonResource $resourceClass */
        $resourceClass = $apiResource::getResourceClass();

        return $resourceClass::make($apiResource);
    }

    /**
     * Create a resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $modelKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize(ApiOperation::CREATE()->getValue() . '-' . $modelKey);

        /** @var ApiResource $apiResource */
        $apiResource = resolve($modelKey);

        $validated = $this->validate($request, $apiResource::getValidationRules());

        $resource = $apiResource::query()->create($validated);

        /** @var JsonResource $resourceClass */
        $resourceClass = $apiResource::getResourceClass();

        return $resourceClass::make($resource)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update a resource.
     *
     * @param Request $request
     * @param ApiResource $apiResource
     * @return JsonResource
     * @throws ValidationException
     */
    public function update(Request $request, ApiResource $apiResource): JsonResource
    {
        $modelKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize(ApiOperation::UPDATE()->getValue() . '-' . $modelKey);

        $validated = $this->validate($request, $apiResource::getValidationRules());

        $apiResource->update($validated);

        /** @var JsonResource $resourceClass */
        $resourceClass = $apiResource::getResourceClass();

        return $resourceClass::make($apiResource);
    }

    /**
     * Delete a resource.
     *
     * @param Request $request
     * @param ApiResource $apiResource
     * @return JsonResource
     * @throws Exception
     */
    public function delete(Request $request, ApiResource $apiResource): JsonResource
    {
        $modelKey = Str::replaceFirst('api/', '', $request->path());

        Gate::authorize(ApiOperation::DELETE()->getValue() . '-' . $modelKey);

        $apiResource->delete();

        /** @var JsonResource $resourceClass */
        $resourceClass = $apiResource::getResourceClass();

        return $resourceClass::make($apiResource);
    }
}
