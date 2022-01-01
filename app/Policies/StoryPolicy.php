<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class StoryPolicy extends AuthPolicy
{
	public string $modelNameSlug = 'story';
}
