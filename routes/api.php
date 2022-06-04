<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

$modelNameSlugs = Config::get('cms.api_routes');

foreach ($modelNameSlugs as $modelNameSlug) {
    $controllerName = \Str::studly($modelNameSlug) . '\ApiController';
    if (!file_exists(__DIR__ . '\..\app\Http\Controllers\Api\\' . $controllerName . '.php')) {
        $controllerName = 'ApiController';
    }
    Route::resource($modelNameSlug, $controllerName);
    // Route::group([
    //     'prefix' => $modelNameSlug,
    //     'as' => $modelNameSlug . '.',
    // ], function () use ($controllerName): void {
    // Route::post('{url}/comment', $controllerName . '@comment')->name('comment');
    // Route::post('{url}/like', $controllerName . '@like')->name('like');
    // Route::get('{url}/likes_count', $controllerName . '@likeCount')->name('like-count');
    // });
}
