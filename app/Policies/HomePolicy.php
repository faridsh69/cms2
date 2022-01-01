<?php

namespace App\Policies;

use App\Cms\Policy;

class HomePolicy extends Policy
{
	public string $modelNameSlug = 'home';
}
