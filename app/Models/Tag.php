<?php

namespace App\Models;

use App\Cms\Model;

class Tag extends Model
{
    public $columns = [
        [
            'name' => 'type',
            'type' => 'string',
            'database' => 'required',
            'rule' => 'required',
            'help' => 'This tag is for which models?',
            'form_type' => 'enum',
            'form_enum_class' => 'ModelType',
            'table' => true,
        ],
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'icon'],
        ['name' => 'activated'],
        ['name' => 'language'],
    ];

    public function modelsWithThisType()
    {
        return $this->belongsToMany(config('cms.config.models_namespace') . $this->type, 'taggables', 'tag_id', 'taggable_id');
    }
}
