<?php

namespace App\Forms;

use App\Cms\Form as CmsForm;
use Request;
use Str;

class Form extends CmsForm
{
	public function __construct()
    {
        $this->modelName = Str::studly(Request::segment(2));
        $modelNamespace = config('cms.config.models_namespace') . $this->modelName;
        $modelRepository = new $modelNamespace();
        $this->modelColumns = $modelRepository->getColumns();
    }
}
