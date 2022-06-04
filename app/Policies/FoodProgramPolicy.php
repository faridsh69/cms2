<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class FoodProgramPolicy extends Policy
{
	public string $modelNameSlug = 'food-program';
}
