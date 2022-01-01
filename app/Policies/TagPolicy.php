<?php

namespace App\Policies;

use App\Cms\Policy;

class TagPolicy extends Policy
{
	public string $modelNameSlug = 'tag';
}
