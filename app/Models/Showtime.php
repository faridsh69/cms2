<?php

namespace App\Models;

use App\Cms\Model;

class Showtime extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'opening_hours'],
        ['name' => 'price'],
        ['name' => 'discount_price'],
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
