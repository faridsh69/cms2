<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class FactorPolicy extends AuthPolicy
{
	public string $modelNameSlug = 'factor';
}
