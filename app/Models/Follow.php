<?php

namespace App\Models;

use App\Cms\Model;

class Follow extends Model
{
    public $columns = [
        ['name' => 'user_id'],
        [
            'name' => 'followable_type',
            'type' => 'string',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
        [
            'name' => 'followable_id',
            'type' => 'unsignedBigInteger',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
    ];

    public function followable()
    {
        return $this->morphTo();
    }
}
