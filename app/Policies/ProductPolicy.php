<?php

namespace App\Policies;

use App\Cms\Policy;

class ProductPolicy extends Policy
{
	public string $modelNameSlug = 'product';
}
