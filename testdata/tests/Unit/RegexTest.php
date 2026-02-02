<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class RegexTest extends TestCase
{
    public function testBasicMatch(): void
    {
        $this->assertEquals(1, preg_match('/hello/', 'hello world'));
        $this->assertEquals(0, preg_match('/goodbye/', 'hello world'));
    }

    public function testCaseInsensitive(): void
    {
        $this->assertEquals(1, preg_match('/HELLO/i', 'hello world'));
    }

    public function testCapturingGroups(): void
    {
        preg_match('/(\w+)@(\w+)\.(\w+)/', 'user@example.com', $matches);

        $this->assertEquals('user', $matches[1]);
        $this->assertEquals('example', $matches[2]);
        $this->assertEquals('com', $matches[3]);
    }

    public function testMatchAll(): void
    {
        preg_match_all('/\d+/', 'a1b2c3', $matches);
        $this->assertEquals(['1', '2', '3'], $matches[0]);
    }

    public function testReplace(): void
    {
        $result = preg_replace('/\s+/', ' ', 'hello    world');
        $this->assertEquals('hello world', $result);
    }

    public function testReplaceCallback(): void
    {
        $result = preg_replace_callback('/\d+/', fn($m) => $m[0] * 2, 'a1b2c3');
        $this->assertEquals('a2b4c6', $result);
    }

    public function testSplit(): void
    {
        $parts = preg_split('/[\s,]+/', 'one two, three');
        $this->assertEquals(['one', 'two', 'three'], $parts);
    }

    public function testWordBoundary(): void
    {
        $this->assertEquals(1, preg_match('/\bcat\b/', 'the cat sat'));
        $this->assertEquals(0, preg_match('/\bcat\b/', 'category'));
    }

    public function testStartEnd(): void
    {
        $this->assertEquals(1, preg_match('/^hello/', 'hello world'));
        $this->assertEquals(1, preg_match('/world$/', 'hello world'));
    }

    public function testOptionalQuantifier(): void
    {
        $this->assertEquals(1, preg_match('/colou?r/', 'color'));
        $this->assertEquals(1, preg_match('/colou?r/', 'colour'));
    }
}
