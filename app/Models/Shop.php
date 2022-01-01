<?php

namespace App\Models;

use App\Cms\Model;

class Shop extends Model
{
    public $columns = [
        ['name' => 'title'],
        ['name' => 'url'],
        ['name' => 'description'],
        ['name' => 'opening_hours'],
        ['name' => 'city'],
        ['name' => 'address'],
        ['name' => 'phone'],
        ['name' => 'telephone'],
        ['name' => 'email'],
        ['name' => 'website'],
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
