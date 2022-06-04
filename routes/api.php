<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

$modelNameSlugs = Config::get('cms.api_routes');

foreach ($modelNameSlugs as $modelNameSlug) {
    $controllerName = \Str::studly($modelNameSlug) . '\ApiController';
    if (!file_exists(__DIR__ . '\..\app\Http\Controllers\Api\\' . $controllerName . '.php')) {
        $controllerName = 'ApiController';
    }
    Route::group([
        'prefix' => $modelNameSlug,
        'as' => $modelNameSlug . '.',
    ], function () use ($controllerName): void {
        Route::resource('list', $controllerName);
        Route::get('category', $controllerName . '@getCategories')->name('category.index');
        Route::get('category/{url?}', $controllerName . '@getCategory')->name('category.show');
        Route::get('tag', $controllerName . '@getTags')->name('tag.index');
        Route::get('tag/{url?}', $controllerName . '@getTag')->name('tag.show');
        Route::post('{url}/comment', $controllerName . '@comment')->name('comment');
        Route::post('{url}/like', $controllerName . '@like')->name('like');
        Route::get('{url}/like/count', $controllerName . '@likeCount')->name('like-count');
    });
}
