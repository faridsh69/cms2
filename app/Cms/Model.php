<?php

namespace App\Cms;

use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{
    use ModelTrait;

	protected $guarded = [];

	protected $hidden = [
		'deleted_at',
	];

	protected $casts = [
        'activated' => 'boolean',
    ];
}
