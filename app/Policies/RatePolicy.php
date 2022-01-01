<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class RatePolicy extends AuthPolicy
{
	public string $modelNameSlug = 'rate';
}
