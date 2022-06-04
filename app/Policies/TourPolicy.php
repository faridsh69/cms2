<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class TourPolicy extends Policy
{
	public string $modelNameSlug = 'tour';
}
