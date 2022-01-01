<?php

namespace App\Policies;

use App\Cms\Policy;

class RestaurantPolicy extends Policy
{
	public string $modelNameSlug = 'restaurant';
}
