<?php

namespace App\Models;

use App\Cms\Model;

class Food extends Model
{
    protected $table = 'foods';

    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'price'],
        ['name' => 'discount_price'],
        ['name' => 'calorie'],
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
