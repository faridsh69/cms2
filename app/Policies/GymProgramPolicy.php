<?php

declare(strict_types=1);

namespace App\Policies;

use App\Cms\Policy;

final class GymProgramPolicy extends Policy
{
    public string $modelNameSlug = 'gym-program';
}
