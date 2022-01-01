<?php

namespace App\Policies;

use App\Cms\Policy;

class FormPolicy extends Policy
{
	public string $modelNameSlug = 'form';
}
