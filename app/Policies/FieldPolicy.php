<?php

namespace App\Policies;

use App\Cms\Policy;

class FieldPolicy extends Policy
{
	public string $modelNameSlug = 'field';
}
