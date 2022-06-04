<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Like extends Model
{
    public $columns = [
        [
            'name' => 'user_id',
        ],
        [
            'name' => 'likeable_type',
            'type' => 'string',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
        [
            'name' => 'likeable_id',
            'type' => 'unsignedBigInteger',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
    ];

    public function likeable()
    {
        return $this->morphTo();
    }
}
