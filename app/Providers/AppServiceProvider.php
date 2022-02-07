<?php

namespace App\Providers;

use App\Cms\CmsServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        CmsServiceProvider::bootCms();
    }
}
