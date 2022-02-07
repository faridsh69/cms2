<?php

namespace App\Models;

use App\Cms\Model;

class Advertise extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'price'],
        ['name' => 'discount_price'],
        ['name' => 'color'],
        ['name' => 'year'],
        ['name' => 'properties'],
        ['name' => 'content'],
        [
            'name' => 'images_gallery',
            'same_column_name' => 'image',
        ],
        [
            'name' => 'videos_gallery',
            'same_column_name' => 'video',
        ],
        ['name' => 'activated'],
        ['name' => 'category_id'],
        ['name' => 'tags'],
        ['name' => 'relateds'],
        ['name' => 'language'],
    ];
}
