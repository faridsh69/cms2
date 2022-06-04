<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class FieldPolicy extends Policy
{
    public string $modelNameSlug = 'field';
}
