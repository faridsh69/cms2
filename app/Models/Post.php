<?php

namespace App\Models;

use App\Cms\Model;

class Post extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'content'],
        ['name' => 'user_image'],
        ['name' => 'user_video'],
        ['name' => 'activated'],
        ['name' => 'category_id'],
        ['name' => 'tags'],
        ['name' => 'relateds'],
        ['name' => 'language'],
    ];
}
