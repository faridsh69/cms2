<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Product extends Model
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
			'name' => 'category_id',
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
			'name' => 'price',
		],
		[
			'name' => 'discount_price',
		],
		[
			'name' => 'inventory',
			'type' => 'bigInteger',
			'database' => 'nullable',
			'rule' => '',
			'help' => '',
			'form_type' => 'none',
			'table' => false,
		],
		[
			'name' => 'order',
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
