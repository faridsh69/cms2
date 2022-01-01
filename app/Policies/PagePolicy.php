<?php

namespace App\Policies;

use App\Cms\Policy;

class PagePolicy extends Policy
{
	public string $modelNameSlug = 'page';
}
