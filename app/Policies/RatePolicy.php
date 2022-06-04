<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\AuthPolicy;

final class RatePolicy extends AuthPolicy
{
    public string $modelNameSlug = 'rate';
}
