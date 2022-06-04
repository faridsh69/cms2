<?php

declare(strict_types=1);

namespace App\Cms;

use App\Models\{Category, Tag, User};
use Str;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 * @coversNothing
 */
abstract class Test extends TestCase
{
    // an aray of models that want to test
    public array $modelNameSlugs;

    // single model to test
    public string $modelNameSlug;

    public $resource_methods = [
        'print',
        'export',
        'datatable',
        'list.index',
        'list.create',
    ];

    public $frontMethods = [
        'index',
        'category.index',
        'tag.index',
    ];

    final public function adminTest(): void
    {
        $this->modelNameSlugs = config('cms.admin_tests');

        foreach ($this->modelNameSlugs as $modelNameSlug) {
            echo "\nResource Testing " . $modelNameSlug . '...';
            $modelName = Str::studly($modelNameSlug);
            $modelNamespace = config('cms.config.models_namespace') . $modelName;
            $modelRepository = new $modelNamespace();

            $user = User::first();
            $this->actingAs($user);

            // redirect
            $this
                ->get(route('admin.' . $modelNameSlug . '.redirect'))
                ->assertRedirect(route('admin.' . $modelNameSlug . '.list.index'));

            // create fake data for store in database
            $factory = new Factory();
            $factory->setModelNameSlug($modelNameSlug);
            $fakeModel = $factory->make();
            $fakeData = $fakeModel->getAttributes();

            // store fake model
            $this
                ->post(route('admin.' . $modelNameSlug . '.list.store', $fakeData))
                ->assertRedirect(route('admin.' . $modelNameSlug . '.list.index'));

            // get fake model that created at this test
            $fakeModel = $modelRepository->orderBy('id', 'desc')->first();

            // show fake model
            $this
                ->get(route('admin.' . $modelNameSlug . '.list.show', $fakeModel))
                ->assertOk();

            // edit fake model
            $this
                ->get(route('admin.' . $modelNameSlug . '.list.edit', $fakeModel))
                ->assertOk();

            // update fake model
            $this
                ->put(route('admin.' . $modelNameSlug . '.list.update', $fakeModel), $fakeData)
                ->assertRedirect(route('admin.' . $modelNameSlug . '.list.index'));

            // delete fake model
            $this
                ->delete(route('admin.' . $modelNameSlug . '.list.destroy', $fakeModel))
                ->assertRedirect(route('admin.' . $modelNameSlug . '.list.index'));

            // restore fake model
            $this
                ->get(route('admin.' . $modelNameSlug . '.list.restore', $fakeModel))
                ->assertRedirect(route('admin.' . $modelNameSlug . '.list.index'));

            // force delete fake model
            $fakeModel->forceDelete();

            foreach ($this->resource_methods as $method) {
                $this
                    ->get(route('admin.' . $modelNameSlug . '.' . $method))
                    ->assertOk();
            }
            echo 'Done!';
        }
    }

    final public function frontTest(): void
    {
        $this->modelNameSlugs = config('cms.front_tests');

        foreach ($this->modelNameSlugs as $modelNameSlug) {
            echo "\nFront Testing " . $modelNameSlug . '...';
            $modelName = Str::studly($modelNameSlug);
            $modelNamespace = config('cms.config.models_namespace') . $modelName;
            $modelRepository = new $modelNamespace();

            $user = User::first();
            $this->actingAs($user);

            // get fake model that created at this test
            $fakeModel = $modelRepository->orderBy('id', 'desc')->first();

            // show fake model
            if ($fakeModel && isset($fakeModel->url) && $fakeModel->url) {
                $this
                    ->get(route('front.' . $modelNameSlug . '.show', $fakeModel->url))
                    ->assertOk();
            }

            // get model category
            $categoryModel = Category::ofType($modelName)->first();
            // show category of model
            if ($categoryModel) {
                $this
                    ->get(route('front.' . $modelNameSlug . '.category.show', $categoryModel->url))
                    ->assertOk();
                echo 'With Category...';
            }

            // get model tag
            $tagModel = Tag::ofType($modelName)->first();

            // show tag of model
            if ($tagModel) {
                $this
                    ->get(route('front.' . $modelNameSlug . '.tag.show', $tagModel->url))
                    ->assertOk();
                echo 'With Tag...';
            }

            foreach ($this->frontMethods as $method) {
                $this
                    ->get(route('front.' . $modelNameSlug . '.' . $method))
                    ->assertOk();
            }
            echo 'Done!';
        }
    }
}
