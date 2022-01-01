<?php

namespace App\Models;

use App\Cms\Model;

class Basket extends Model
{
    public $columns = [
        ['name' => 'activated'],
        ['name' => 'user_id'],
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('count');
    }

    public static function getTotalPriceByRestaurantId($restaurantId)
    {
        // $restaurantId
        return 12;
    }
}
