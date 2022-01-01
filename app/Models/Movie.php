<?php

namespace App\Models;

use App\Cms\Model;

class Movie extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'country'],
        ['name' => 'year'],
        ['name' => 'director'],
        ['name' => 'properties'],
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
