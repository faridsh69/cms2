<?php

namespace App\Policies;

use App\Cms\Policy;

class GymActionPolicy extends Policy
{
	public string $modelNameSlug = 'gym-action';
}
