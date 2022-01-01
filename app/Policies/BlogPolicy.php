<?php

namespace App\Policies;

use App\Cms\Policy;

class BlogPolicy extends Policy
{
	public string $modelNameSlug = 'blog';
}
