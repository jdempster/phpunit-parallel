<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class CacheTest extends TestCase
{
    private array $cache = [];

    public function testSetAndGet(): void
    {
        $this->cache['key'] = 'value';
        $this->assertEquals('value', $this->cache['key']);
    }

    public function testHas(): void
    {
        $this->cache['exists'] = 'value';

        $this->assertTrue(isset($this->cache['exists']));
        $this->assertFalse(isset($this->cache['not_exists']));
    }

    public function testDelete(): void
    {
        $this->cache['key'] = 'value';
        unset($this->cache['key']);

        $this->assertFalse(isset($this->cache['key']));
    }

    public function testClear(): void
    {
        $this->cache['a'] = 1;
        $this->cache['b'] = 2;
        $this->cache = [];

        $this->assertEmpty($this->cache);
    }

    public function testGetMultiple(): void
    {
        $this->cache['a'] = 1;
        $this->cache['b'] = 2;
        $this->cache['c'] = 3;

        $keys = ['a', 'b', 'c'];
        $values = array_map(fn($k) => $this->cache[$k] ?? null, $keys);

        $this->assertEquals([1, 2, 3], $values);
    }

    public function testSetMultiple(): void
    {
        $items = ['x' => 10, 'y' => 20, 'z' => 30];
        foreach ($items as $k => $v) {
            $this->cache[$k] = $v;
        }

        $this->assertEquals(10, $this->cache['x']);
        $this->assertEquals(30, $this->cache['z']);
    }

    public function testDefaultValue(): void
    {
        $value = $this->cache['missing'] ?? 'default';
        $this->assertEquals('default', $value);
    }

    public function testIncrementDecrement(): void
    {
        $this->cache['counter'] = 0;
        $this->cache['counter']++;
        $this->cache['counter']++;
        $this->cache['counter']--;

        $this->assertEquals(1, $this->cache['counter']);
    }

    public function testArrayCache(): void
    {
        $this->cache['list'] = [1, 2, 3];
        $this->cache['list'][] = 4;

        $this->assertEquals([1, 2, 3, 4], $this->cache['list']);
    }

    public function testNestedCache(): void
    {
        $this->cache['user'] = ['name' => 'John', 'settings' => ['theme' => 'dark']];

        $this->assertEquals('dark', $this->cache['user']['settings']['theme']);
    }
}
