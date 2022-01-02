<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Str;

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
            $category['url'] = Str::slug($category['title']);
            Category::firstOrCreate($category);
            $order ++;
        }
    }
}
