<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class FollowPolicy extends AuthPolicy
{
	public string $modelNameSlug = 'follow';
}
