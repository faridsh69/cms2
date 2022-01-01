<?php

namespace App\Policies;

use App\Cms\Policy;

class ActivityPolicy extends Policy
{
	public string $modelNameSlug = 'activity';
}
