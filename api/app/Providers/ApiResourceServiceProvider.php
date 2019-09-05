<?php

namespace App\Providers;

use App\Contracts\ApiResource;
use App\Enumerations\ApiOperation;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Gears\ClassFinder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ApiResourceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** @noinspection PhpIncludeInspection */
        $finder = new ClassFinder(require base_path('vendor/autoload.php'));

        foreach ($finder->namespace(env('ENTITY_NAMESPACE', 'App\\Models'))
                     ->implements(ApiResource::class)->search() as $file => $class) {
            $resourceKey = $class::getResourceKey();

            $this->app->bind(Str::plural($resourceKey), $class);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** @noinspection PhpIncludeInspection */
        $finder = new ClassFinder(require base_path('vendor/autoload.php'));

        foreach ($finder->namespace(env('ENTITY_NAMESPACE', 'App\\Models'))
                     ->implements(ApiResource::class)->search() as $file => $class) {
            $resourceKey = $class::getResourceKey();

            $this->mapApiRoutes($resourceKey, $class);

            $this->mapApiGates($resourceKey, $class);
        }
    }

    /**
     * Map the CRUD API routes for the given resource key and class.
     *
     * @param string $resourceKey
     * @param string $resourceClass
     * @return void
     */
    protected function mapApiRoutes(string $resourceKey, string $resourceClass)
    {
        // Bind the camel-case name to the model class for route model binding
        Route::model($resourceKey, $resourceClass);

        Route::prefix('api')->middleware('api')->group(function () use ($resourceKey) {
            // All route (GET /api/modelClasses)
            Route::get(Str::plural($resourceKey), ApiController::class . '@all');

            // One route (GET /api/modelClasses/{id})
            Route::get(sprintf('%s/{%s}', Str::plural($resourceKey), $resourceKey),
                ApiController::class . '@one')->where($resourceKey, '\d+');

            // Create route (POST /api/modelClasses)
            Route::post(Str::plural($resourceKey), ApiController::class . '@create');

            // Update route (PUT /api/modelClasses/{id})
            Route::put(sprintf('%s/{%s}', Str::plural($resourceKey), $resourceKey),
                ApiController::class . '@update')->where($resourceKey, '\d+');

            // Delete route (DELETE /api/modelClasses/{id})
            Route::delete(sprintf('%s/{%s}', Str::plural($resourceKey), $resourceKey),
                ApiController::class . '@delete')->where($resourceKey, '\d+');
        });
    }

    /**
     * Create gates for the given resource key and class.
     *
     * @param string $resourceKey
     * @param string $resourceClass
     * @return void
     */
    protected function mapApiGates(string $resourceKey, string $resourceClass)
    {
        // All operation
        $allRoleOrGate = $resourceClass::getAllRoleOrGate();
        if (is_callable($allRoleOrGate)) {
            Gate::define(ApiOperation::ALL()->getValue() . '-' . Str::plural($resourceKey), $allRoleOrGate);

        } else {
            Gate::define(ApiOperation::ALL()->getValue() . '-' . Str::plural($resourceKey),
                function (User $user) use ($allRoleOrGate) {
                return $user->hasRole($allRoleOrGate);
            });
        }


        // One operation
        $oneRoleOrGate = $resourceClass::getOneRoleOrGate();
        if (is_callable($oneRoleOrGate)) {
            Gate::define(ApiOperation::ONE()->getValue() . '-' . Str::plural($resourceKey), $oneRoleOrGate);

        } else {
            Gate::define(ApiOperation::ONE()->getValue() . '-' . Str::plural($resourceKey),
                function (User $user, ApiResource $resource) use ($oneRoleOrGate) {
                    return $user->hasRole($oneRoleOrGate);
                });
        }


        // Create operation
        $createRoleOrGate = $resourceClass::getCreateRoleOrGate();
        if (is_callable($createRoleOrGate)) {
            Gate::define(ApiOperation::CREATE()->getValue() . '-' . Str::plural($resourceKey), $createRoleOrGate);

        } else {
            Gate::define(ApiOperation::CREATE()->getValue() . '-' . Str::plural($resourceKey),
                function (User $user, ApiResource $resource) use ($createRoleOrGate) {
                    return $user->hasRole($createRoleOrGate);
                });
        }


        // Update operation
        $updateRoleOrGate = $resourceClass::getUpdateRoleOrGate();
        if (is_callable($updateRoleOrGate)) {
            Gate::define(ApiOperation::UPDATE()->getValue() . '-' . Str::plural($resourceKey), $updateRoleOrGate);

        } else {
            Gate::define(ApiOperation::UPDATE()->getValue() . '-' . Str::plural($resourceKey),
                function (User $user, ApiResource $resource) use ($updateRoleOrGate) {
                    return $user->hasRole($updateRoleOrGate);
                });
        }


        // Delete operation
        $deleteRoleOrGate = $resourceClass::getDeleteRoleOrGate();
        if (is_callable($deleteRoleOrGate)) {
            Gate::define(ApiOperation::DELETE()->getValue() . '-' . Str::plural($resourceKey), $deleteRoleOrGate);

        } else {
            Gate::define(ApiOperation::DELETE()->getValue() . '-' . Str::plural($resourceKey),
                function (User $user, ApiResource $resource) use ($deleteRoleOrGate) {
                    return $user->hasRole($deleteRoleOrGate);
                });
        }
    }
}
