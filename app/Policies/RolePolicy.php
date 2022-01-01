<?php

namespace App\Policies;

use App\Cms\Policy;

class RolePolicy extends Policy
{
	public string $modelNameSlug = 'role';
}
