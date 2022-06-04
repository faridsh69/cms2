<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class TagPolicy extends Policy
{
	public string $modelNameSlug = 'tag';
}
