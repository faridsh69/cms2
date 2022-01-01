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

	public function setTitle(string $title = null)
	{
	    $activity = new Activity();
	    $activity->title = $title;
	    $activity->activated = 1;

	    return $activity;
	}
}
