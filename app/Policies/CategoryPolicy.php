<?php

namespace App\Policies;

use App\Cms\Policy;

class CategoryPolicy extends Policy
{
	public string $modelNameSlug = 'category';
}
