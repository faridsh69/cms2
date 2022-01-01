<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class Seeder002Tags extends Seeder
{
    public function run()
    {
        $tags = [
        	[
	        	'title' => 'Development',
        	],
        	[
	        	'title' => 'Movie',
        	],
        	[
	        	'title' => '2020',
        	],
            [
                'title' => 'Fitness',
            ],
            [
                'title' => 'Funny',
            ],
        ];

    	foreach($tags as $tag)
    	{
        	Tag::firstOrCreate($tag);
    	}
    }
}
