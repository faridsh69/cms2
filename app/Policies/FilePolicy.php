<?php

namespace App\Policies;

use App\Cms\Policy;

class FilePolicy extends Policy
{
	public string $modelNameSlug = 'file';
}
