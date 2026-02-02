<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        $this->assertTrue(true);
    }

    public function testFalseIsFalse(): void
    {
        $this->assertFalse(false);
    }

    public function testArrayHasKey(): void
    {
        $array = ['foo' => 'bar'];
        $this->assertArrayHasKey('foo', $array);
    }
}
