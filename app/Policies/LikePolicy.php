<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class LikePolicy extends AuthPolicy
{
	public string $modelNameSlug = 'like';
}
