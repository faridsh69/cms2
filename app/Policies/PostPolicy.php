<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class PostPolicy extends AuthPolicy
{
	public string $modelNameSlug = 'post';
}
