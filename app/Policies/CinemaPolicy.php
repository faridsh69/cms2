<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class CinemaPolicy extends Policy
{
	public string $modelNameSlug = 'cinema';
}
