<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Str;

class BaseFactory extends Factory
{
    protected $model = 'App\Models\User';

    public $modelNameSlug = '';

    public function setModelNameSlug(string $modelNameSlug)
    {
        $this->modelNameSlug = $modelNameSlug;

        return $this;
    }

    public function definition()
    {
        $modelName = Str::studly($this->modelNameSlug);
        $models_namespace = config('cms.config.models_namespace') . $modelName;
        $modelRepository = new $models_namespace();
        $modelColumns = $modelRepository->getColumns();

        $output = [];
        $random_int = rand(1000, 9999);
        foreach($modelColumns as $column){
            $fake_data = null;
            $name = $column['name'];
            $type = $column['type'];
            $form_type = isset($column['form_type']) ? $column['form_type'] : '';
            $database = isset($column['database']) ? $column['database'] : null;

            if($name === 'url'){
                $fake_data = $this->modelNameSlug . '-url-' . $random_int;
            }
            elseif($name === 'title'){
                $fake_data = $modelName . ' title ' . $random_int;
            }
            elseif($name === 'description'){
                $fake_data = $this->faker->realText(100);
            }
            elseif($name === 'content'){
                $fake_data = '<h1>h1</h1><h2>h2</h2>' . $this->faker->realText(400);
            }
            elseif($name === 'canonical_url'){
                $fake_data = null;
            }
            elseif($name === 'google_index'){
                $fake_data = 0;
            }
            elseif($name === 'activated'){
                $fake_data = 1;
            }
            elseif($form_type === 'file')
            {
                $file_accept = isset($column['file_accept']) ? $column['file_accept'] : null;
                $file_manager = isset($column['file_manager']) ? $column['file_manager'] : null;
                
                if($file_accept === 'image'){
                    $fileName = 'image.png';
                }elseif($file_accept === 'video'){
                    $fileName = 'video.mp4';
                }elseif($file_accept === 'audio'){
                    $fileName = 'audio.mp3';
                }else{
                    $fileName = 'doc.pdf';
                }

                if ($file_manager) {
                    $fake_data = asset('seeder-test-files/' . $fileName);
                }else{
                    $uploadFileTest = public_path() . '/cdn/seeder-test-files/' . $fileName;
                    $fake_data = new UploadedFile($uploadFileTest, $uploadFileTest);
                }
            }
            elseif($name === 'keywords'){
                $fake_data = $this->faker->realText(100);
            }
            elseif($name === 'icon'){
                $fake_data = 'fa fa-book';
            }
            elseif($name === 'full_name'){
                $fake_data = $this->faker->name();
            }
            elseif($name === 'phone' || $name === 'telephone'){
                $fake_data = '+49153000';
            }
            elseif($name === 'national_code'){
                $fake_data = '1270739034';
            }
            elseif($name === 'postal_code'){
                $fake_data = '18321';
            }
            elseif($name === 'country'){
                $fake_data = $this->faker->countryCode();
            }
            elseif($name === 'province'){
                $fake_data = $this->faker->state();
            }
            elseif($name === 'city'){
                $fake_data = $this->faker->city();
            }
            elseif($name === 'address'){
                $fake_data = $this->faker->address();
            }
            elseif($name === 'latitude'){
                $fake_data = $this->faker->latitude();
            }
            elseif($name === 'longitude'){
                $fake_data = $this->faker->longitude();
            }
            elseif($name === 'email'){
                $fake_data = $this->faker->email();
            }
            elseif($name === 'language'){
                $fake_data = 'en';
            }
            elseif($name === 'password'){
                $password = '1111';
                $fake_data = $password;
            }
            elseif($type === 'array'){
                $random_related_count = rand(2, 4);
                $fake_data = range(1, $random_related_count);
            }
            elseif($database === 'none'){
                continue;
            }
            elseif($type === 'text'){
                $fake_data = 'Fake ' . $this->faker->realText(400);
            }
            elseif($type === 'boolean'){
                $fake_data = 0;
            }
            elseif($type === '' || $type === 'bigInteger' || $type === 'integer' || $type === 'tinyInteger' || $type === 'unsignedBigInteger'){
                $fake_data = 1;
            }
            elseif($type === 'string'){
                $fake_data = 'Fake ' . $this->faker->realText(15);
            }

            $output[$name] = $fake_data;
        }
        if(isset($password)){
            $output['password_confirmation'] = $password;
        }

        return $output;
    }
}
