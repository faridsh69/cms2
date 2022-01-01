<?php

namespace App\Policies;

use App\Cms\Policy;

class BlockPolicy extends Policy
{
	public string $modelNameSlug = 'block';
}
