<?php

namespace App\Cms;

use Illuminate\Database\Seeder as LaravelSeeder;
use Str;

class Seeder extends LaravelSeeder
{
	public function run()
	{
		$modelNameSlugs = config('cms.seeder');

        foreach($modelNameSlugs as $modelNameSlug)
        {
            echo 'Seeding ' . $modelNameSlug . '...';
            $modelName = Str::studly($modelNameSlug);
            $modelNamespace = config('cms.config.models_namespace') . $modelName;
            $modelRepository = new $modelNamespace();
    		for($i = 0; $i < 4; $i ++){
                // Create fake data and store them in database
                $factory = new Factory();
                $factory->setModelNameSlug($modelNameSlug);
                $fakeModel = $factory->make();
                $fakeData = $fakeModel->getAttributes();

        		$modelRepository->saveWithRelations($fakeData);
        	}
	        echo "Done!\n";
	    }
	}
}
