<?php

namespace App\Policies;

use App\Cms\Policy;

class ShowtimePolicy extends Policy
{
	public string $modelNameSlug = 'showtime';
}
