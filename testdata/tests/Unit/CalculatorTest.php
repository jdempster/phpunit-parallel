<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class CalculatorTest extends TestCase
{
    public function testAddition(): void
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    public function testSubtraction(): void
    {
        $result = 10 - 5;
        $this->assertEquals(5, $result);
    }

    public function testMultiplication(): void
    {
        $result = 3 * 4;
        $this->assertEquals(12, $result);
    }

    public function testDivision(): void
    {
        $result = 20 / 4;
        $this->assertEquals(5, $result);
    }

    public function testModulo(): void
    {
        $result = 10 % 3;
        $this->assertEquals(1, $result);
    }

    public function testPower(): void
    {
        $result = pow(2, 8);
        $this->assertEquals(256, $result);
    }

    public function testSquareRoot(): void
    {
        $result = sqrt(16);
        $this->assertEquals(4, $result);
    }

    public function testAbsoluteValue(): void
    {
        $this->assertEquals(5, abs(-5));
        $this->assertEquals(5, abs(5));
    }

    public function testRounding(): void
    {
        $this->assertEquals(4, round(3.7));
        $this->assertEquals(3, floor(3.7));
        $this->assertEquals(4, ceil(3.2));
    }

    public function testMaxMin(): void
    {
        $this->assertEquals(10, max(1, 5, 10, 3));
        $this->assertEquals(1, min(1, 5, 10, 3));
    }
}
