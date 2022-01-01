<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        foreach($categories as $category){
            $category['language'] = 'en';
            $category['order'] = $order;
            $category['url'] = Str::slug($category['title']);
            Category::firstOrCreate($category);
            $order ++;
        }
    }
}
