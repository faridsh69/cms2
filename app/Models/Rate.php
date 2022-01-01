<?php

namespace App\Models;

use App\Cms\Model;

class Rate extends Model
{
    public $columns = [
        ['name' => 'user_id'],
        [
            'name' => 'rateable_type',
            'type' => 'string',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
        [
            'name' => 'rateable_id',
            'type' => 'unsignedBigInteger',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
    ];

    public function rateable()
    {
        return $this->morphTo();
    }
}
