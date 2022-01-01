<?php

namespace App\Policies;

use App\Cms\Policy;

class ModulePolicy extends Policy
{
	public string $modelNameSlug = 'module';
}
