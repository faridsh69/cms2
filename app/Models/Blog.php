<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Blog extends Model
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
            'name' => 'content',
        ],
        [
            'name' => 'image',
        ],
        [
            'name' => 'video',
        ],
        [
            'name' => 'audio',
        ],
        [
            'name' => 'activated',
        ],
        [
            'name' => 'google_index',
        ],
        [
            'name' => 'canonical_url',
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
}
