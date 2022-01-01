<?php

namespace App\Models;

use App\Cms\Model;

class Blog extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'content'],
        ['name' => 'image'],
        ['name' => 'activated'],
        ['name' => 'google_index'],
        ['name' => 'canonical_url'],
        ['name' => 'category_id'],
        ['name' => 'tags'],
        ['name' => 'relateds'],
        ['name' => 'language'],
    ];
}
