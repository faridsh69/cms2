<?php

namespace App\Cms;

use Maatwebsite\Excel\Concerns\ToModel;

class Import implements ToModel
{
    use CmsMainTrait;

    public $modelFields;

    public $excelFields;

    public function model(array $row)
    {
        foreach($this->modelFields as $key => $modelField)
        {
            $this->excelFields[$modelField] = $row[$key];
        }
        return new $this->modelNamespace($this->excelFields);
    }

    public function setModelName($modelName)
    {
        $this->modelNamespace = config('cms.config.models_namespace') . $modelName;
        $this->modelRepository = new $this->modelNamespace();
        $this->modelFields = collect($this->modelRepository->getColumns())->pluck('name')->toArray();
        $this->excelFields = [];
    }
}
