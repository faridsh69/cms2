<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->bootAdminRoutes();
        $this->bootAuthRoutes();
        $this->bootFilemanagerRoutes();
        $this->bootFrontRoutes();
        $this->bootApiRoutes();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(360)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function bootAdminRoutes(): void
    {
        Route::namespace($this->namespace . '\Admin')
            ->as('admin.')
            ->prefix('admin')
            ->middleware(['web', 'auth'])
            ->group(base_path('routes/admin.php'));
    }

    protected function bootApiRoutes(): void
    {
        Route::namespace($this->namespace . '\Api')
            ->as('api.')
            ->prefix('api')
            // ->middleware(['api', 'throttle:' . config('setting-developer.throttle')])
            ->middleware(['api'])
            ->group(base_path('routes/api.php'));
    }

    protected function bootAuthRoutes(): void
    {
        Route::namespace($this->namespace . '\Auth')
            ->as('auth.')
            ->prefix('auth')
            ->middleware('web')
            ->group(base_path('routes/auth.php'));
    }

    protected function bootFrontRoutes(): void
    {
        Route::namespace($this->namespace . '\Front')
            ->as('front.')
            ->middleware('web')
            ->group(base_path('routes/front.php'));
    }

    protected function bootFilemanagerRoutes(): void
    {
        Route::group(['prefix' => 'admin/filemanager', 'middleware' => ['web', 'auth', 'can:index,App\Models\File']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
    }
}
