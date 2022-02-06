<?php

namespace App\Providers;

use Cache;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $seconds = 1;
        $this->policies = Cache::remember('policies', $seconds, function () {
            $modelNameSlugs = config('cms.policies');
            $models_namespace = config('cms.config.models_namespace');
            $policies = [];
            foreach ($modelNameSlugs as $modelNameSlug) {
                $modelName = Str::studly($modelNameSlug);
                $modelNamespace = $models_namespace . $modelName;
                $model_policy = 'App\Policies\\' . $modelName . 'Policy';
                $policies[$modelNamespace] = $model_policy;
            }

            return $policies;
        });
        $this->registerPolicies();

        Gate::define('manage', function ($user, $page) {
            return $user->can($page . '_manager');
        });

        Passport::routes();
    }
}
