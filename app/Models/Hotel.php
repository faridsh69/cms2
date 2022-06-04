<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Hotel extends Model
{
	public $columns = [
		[
			'name' => 'title',
		],
		[
			'name' => 'url',
		],
		[
			'name' => 'description',
		],
		[
			'name' => 'price',
		],
		[
			'name' => 'discount_price',
		],
		[
			'name' => 'city',
		],
		[
			'name' => 'address',
		],
		[
			'name' => 'phone',
		],
		[
			'name' => 'telephone',
		],
		[
			'name' => 'email',
		],
		[
			'name' => 'website',
		],
		[
			'name' => 'year',
		],
		[
			'name' => 'rooms',
		],
		[
			'name' => 'properties',
		],
		[
			'name' => 'content',
		],
		[
			'name' => 'image',
		],
		[
			'name' => 'video',
		],
		[
			'name' => 'activated',
		],
		[
			'name' => 'category_id',
		],
		[
			'name' => 'tags',
		],
		[
			'name' => 'relateds',
		],
		[
			'name' => 'language',
		],
	];
}
