<?php

namespace App\Policies;

use App\Cms\Policy;

class PermissionPolicy extends Policy
{
	public string $modelNameSlug = 'permission';
}
