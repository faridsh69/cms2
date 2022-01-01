<?php

namespace App\Policies;

use App\Cms\AuthPolicy;

class AdvertisePolicy extends AuthPolicy
{
	public string $modelNameSlug = 'advertise';
}
