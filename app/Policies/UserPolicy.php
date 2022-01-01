<?php

namespace App\Policies;

use App\Cms\Policy;

class UserPolicy extends Policy
{
	public string $modelNameSlug = 'user';
}
