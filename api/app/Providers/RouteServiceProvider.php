<?php

namespace App\Providers;
use App\Http\Controllers\ApiController;
use Gears\ClassFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $finder = new ClassFinder(require base_path('vendor/autoload.php'));

        foreach ($finder->namespace(env('ENTITY_NAMESPACE', 'App\\Models'))->extends(Model::class)
                 as $file => $class) {

            // Api\Models\ModelClass -> modelClass
            $modelCamel = Str::camel(class_basename($class));

            // Bind the camel-case name to the model class for route model binding
            Route::model($modelCamel, $class);

            Route::prefix('api')->middleware('api')->group(function () use ($modelCamel) {
                // All route (GET /api/modelClasses)
                Route::get(Str::plural($modelCamel), ApiController::class . '@all');

                // One route (GET /api/modelClasses/{id})
                Route::get(sprintf('%s/{%s}', Str::plural($modelCamel), $modelCamel),
                    ApiController::class . '@one')->where($modelCamel, '\d+');

                // Create route (POST /api/modelClasses)
                Route::post(Str::plural($modelCamel), ApiController::class . '@create');

                // Update route (PUT /api/modelClasses/{id})
                Route::put(sprintf('%s/{%s}', Str::plural($modelCamel), $modelCamel),
                    ApiController::class . '@update')->where($modelCamel, '\d+');

                // Delete route (DELETE /api/modelClasses/{id})
                Route::delete(sprintf('%s/{%s}', Str::plural($modelCamel), $modelCamel),
                    ApiController::class . '@delete')->where($modelCamel, '\d+');
            });
        }

        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
