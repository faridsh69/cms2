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
            	'type' => 'Blog',
                'title' => 'Health',
            ],
            [
            	'type' => 'Blog',
                'title' => 'Financial',
            ],
            [
            	'type' => 'Blog',
                'title' => 'Social',
            ],
            [
            	'type' => 'Blog',
                'title' => 'Personality',
            ],
        ];

        $order = 1;
        foreach ($categories as $category)
        {
            $category['language'] = 'en';
            $category['order'] = $order;
            $category['url'] = Str::slug($category['title']);
            Category::firstOrCreate($category);
            $order ++;
        }
    }
}
