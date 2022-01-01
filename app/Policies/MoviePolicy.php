<?php

namespace App\Policies;

use App\Cms\Policy;

class MoviePolicy extends Policy
{
	public string $modelNameSlug = 'movie';
}
