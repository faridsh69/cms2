<?php

declare(strict_types=1);

namespace App\Models;

use App\Cms\Model;

final class Like extends Model
{
	protected $appends = ['user'];

	public $columns = [
		[
			'name' => 'user_id',
			'type' => 'unsignedBigInteger',
			'database' => 'required',
			'relation' => 'users',
			'rule' => 'required|exists:users,id',
			'help' => '',
			'form_type' => 'entity',
			'class' => 'App\Models\User',
			'property' => 'email',
			'property_key' => 'id',
			'multiple' => false,
			'table' => true,
		],
		[
			'name' => 'type',
			'type' => 'string',
			'database' => 'required',
			'rule' => 'required|in:like,dislike',
			'help' => 'type is like or dislike?',
			'form_type' => 'enum',
			'form_enum_class' => 'LikeType',
			'table' => true,
		],
		[
			'name' => 'likeable_type',
			'type' => 'string',
			'database' => 'required',
			'rule' => 'required|in:blog,product', // @TODO implement enum for like models
			'help' => '',
			'form_type' => '',
			'table' => true,
		],
		[
			'name' => 'likeable_id',
			'type' => 'unsignedBigInteger',
			'database' => 'required',
			'rule' => 'required|numeric',
			'help' => '',
			'form_type' => '',
			'table' => true,
		],
	];

	public function likeable()
	{
		return $this->morphTo();
	}
}
