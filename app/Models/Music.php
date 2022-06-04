<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Music extends Model
{
    public $columns = [
        [
            'name' => 'title',
        ],
        [
            'name' => 'url',
        ],
        [
            'name' => 'description',
        ],
        [
            'name' => 'country',
        ],
        [
            'name' => 'year',
        ],
        [
            'name' => 'singer',
        ],
        [
            'name' => 'properties',
        ],
        [
            'name' => 'content',
        ],
        [
            'name' => 'audio',
        ],
        [
            'name' => 'image',
        ],
        [
            'name' => 'activated',
        ],
        [
            'name' => 'category_id',
        ],
        [
            'name' => 'tags',
        ],
        [
            'name' => 'relateds',
        ],
        [
            'name' => 'language',
        ],
    ];

    protected $table = 'musics';
}
