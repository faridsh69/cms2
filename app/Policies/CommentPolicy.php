<?php

namespace App\Policies;

use App\Cms\Policy;

class CommentPolicy extends Policy
{
	public string $modelNameSlug = 'comment';
}
