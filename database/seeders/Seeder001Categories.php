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

            [
            	'type' => 'Food',
                'title' => 'Salad',
            ],
            [
            	'type' => 'Food',
                'title' => 'Appetizer',
            ],
            [
            	'type' => 'Food',
                'title' => 'Autumn Special',
            ],
            [
            	'type' => 'Food',
                'title' => 'Breakfast',
            ],
            [
            	'type' => 'Food',
                'title' => 'Pizza',
            ],
            [
            	'type' => 'Food',
                'title' => 'Main Course',
            ],
            [
            	'type' => 'Food',
                'title' => 'Coffee',
            ],
            [
            	'type' => 'Food',
                'title' => 'Freak Shake',
            ],
            [
            	'type' => 'Food',
                'title' => 'Cake',
            ],
            [
            	'type' => 'Food',
                'title' => 'Cold Drinks',
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
