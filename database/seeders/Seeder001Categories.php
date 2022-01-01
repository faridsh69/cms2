<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class Seeder001Categories extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'title' => 'Health',
            ],
            [
                'title' => 'Financial',
            ],
            [
                'title' => 'Social',
            ],
            [
                'title' => 'Personality',
            ],
        ];

        $order = 1;
        foreach ($categories as $category)
        {
            $category['type'] = 'Blog';
            $category['language'] = 'en';
            $category['order'] = $order;
            Category::firstOrCreate($category);
            $order ++;
        }
    }
}
