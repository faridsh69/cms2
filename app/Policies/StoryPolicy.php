<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\AuthPolicy;

final class StoryPolicy extends AuthPolicy
{
	public string $modelNameSlug = 'story';
}
