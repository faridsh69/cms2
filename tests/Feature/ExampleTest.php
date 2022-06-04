<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 *
 * @small
 * @coversNothing
 */
final class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testExample(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
