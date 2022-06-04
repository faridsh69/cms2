<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class ActivityPolicy extends Policy
{
    public string $modelNameSlug = 'activity';
}
