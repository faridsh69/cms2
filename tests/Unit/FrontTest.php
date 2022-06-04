<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Cms\Test;

/**
 * @internal
 *
 * @small
 * @coversNothing
 */
final class FrontTest extends Test
{
    public function test(): void
    {
        $this->frontTest();
    }
}
