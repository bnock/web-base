<?php

namespace App\Providers;

use App\Contracts\ApiResource;
use App\Http\Controllers\ApiController;
use Gears\ClassFinder;
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
                     ->extends(ApiResource::class) as $file => $class) {
            // Api\Models\ModelClass -> modelClass
            $modelCamel = Str::camel(class_basename($class));

            $this->app->bind(Str::plural($modelCamel), $class);
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
                     ->extends(ApiResource::class) as $file => $class) {
            // Api\Models\ModelClass -> modelClass
            $modelCamel = Str::camel(class_basename($class));

            $this->mapApiRoutes($modelCamel, $class);
        }
    }

    /**
     * Map the CRUD API routes for the given model name and class.
     *
     * @param string $modelName
     * @param string $modelClass
     * @return void
     */
    protected function mapApiRoutes(string $modelName, string $modelClass)
    {
        // Bind the camel-case name to the model class for route model binding
        Route::model($modelName, $modelClass);

        Route::prefix('api')->middleware('api')->group(function () use ($modelName) {
            // All route (GET /api/modelClasses)
            Route::get(Str::plural($modelName), ApiController::class . '@all');

            // One route (GET /api/modelClasses/{id})
            Route::get(sprintf('%s/{%s}', Str::plural($modelName), $modelName),
                ApiController::class . '@one')->where($modelName, '\d+');

            // Create route (POST /api/modelClasses)
            Route::post(Str::plural($modelName), ApiController::class . '@create');

            // Update route (PUT /api/modelClasses/{id})
            Route::put(sprintf('%s/{%s}', Str::plural($modelName), $modelName),
                ApiController::class . '@update')->where($modelName, '\d+');

            // Delete route (DELETE /api/modelClasses/{id})
            Route::delete(sprintf('%s/{%s}', Str::plural($modelName), $modelName),
                ApiController::class . '@delete')->where($modelName, '\d+');
        });
    }
}
