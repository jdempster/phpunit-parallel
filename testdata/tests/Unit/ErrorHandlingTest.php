<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class ErrorHandlingTest extends TestCase
{
    public function testBasicException(): void
    {
        $this->expectException(Exception::class);
        throw new Exception('Test exception');
    }

    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Custom message');
        throw new Exception('Custom message');
    }

    public function testExceptionCode(): void
    {
        $this->expectExceptionCode(42);
        throw new Exception('Error', 42);
    }

    public function testInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        throw new InvalidArgumentException('Invalid argument');
    }

    public function testRuntimeException(): void
    {
        $this->expectException(RuntimeException::class);
        throw new RuntimeException('Runtime error');
    }

    public function testTryCatch(): void
    {
        $caught = false;

        try {
            throw new Exception('Test');
        } catch (Exception $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }

    public function testFinally(): void
    {
        $executed = false;

        try {
            $value = 1;
        } finally {
            $executed = true;
        }

        $this->assertTrue($executed);
    }

    public function testGetExceptionInfo(): void
    {
        $exception = new Exception('Test', 100);

        $this->assertEquals('Test', $exception->getMessage());
        $this->assertEquals(100, $exception->getCode());
        $this->assertIsString($exception->getFile());
        $this->assertIsInt($exception->getLine());
    }

    public function testPreviousException(): void
    {
        $previous = new Exception('Previous');
        $current = new Exception('Current', 0, $previous);

        $this->assertSame($previous, $current->getPrevious());
    }

    public function testExceptionChaining(): void
    {
        try {
            try {
                throw new Exception('Inner');
            } catch (Exception $e) {
                throw new Exception('Outer', 0, $e);
            }
        } catch (Exception $e) {
            $this->assertEquals('Outer', $e->getMessage());
            $this->assertEquals('Inner', $e->getPrevious()->getMessage());
        }
    }
}
