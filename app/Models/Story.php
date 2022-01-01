<?php

namespace App\Models;

use App\Cms\Model;

class Story extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'content'],
        ['name' => 'image'],
        ['name' => 'video'],
        ['name' => 'activated'],
        ['name' => 'category_id'],
        ['name' => 'tags'],
        ['name' => 'relateds'],
        ['name' => 'language'],
    ];
}
