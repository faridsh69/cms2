<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\AuthPolicy;

final class AdvertisePolicy extends AuthPolicy
{
	public string $modelNameSlug = 'advertise';
}
