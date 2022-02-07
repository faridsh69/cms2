<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

$modelNameSlugs = Config::get('cms.api_routes');

foreach($modelNameSlugs as $modelNameSlug)
{
	$controller_name = \Str::studly($modelNameSlug) . '\ApiController';
	if (! file_exists(__DIR__ . '\..\app\Http\Controllers\Api\\' . $controller_name . '.php'))
	{
		$controller_name = 'ApiController';
	}
	Route::group(['prefix' => $modelNameSlug, 'as' => $modelNameSlug . '.'], function () use ($controller_name) {
		Route::get('', $controller_name . '@index')->name('index');
		Route::get('category', $controller_name . '@getCategories')->name('category.index');
		Route::get('category/{url?}', $controller_name . '@getCategory')->name('category.show');
		Route::get('tag', $controller_name . '@getTags')->name('tag.index');
		Route::get('tag/{url?}', $controller_name . '@getTag')->name('tag.show');
		Route::get('{url}', $controller_name . '@show')->name('show');
		Route::post('{url}/comment', $controller_name . '@comment')->name('comment');
		Route::post('{url}/like', $controller_name . '@like')->name('like');
		Route::get('{url}/like/count', $controller_name . '@likeCount')->name('like-count');
	});
}
