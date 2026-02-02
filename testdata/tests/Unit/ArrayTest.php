<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class ArrayTest extends TestCase
{
    public function testArrayMerge(): void
    {
        $result = array_merge([1, 2], [3, 4]);
        $this->assertEquals([1, 2, 3, 4], $result);
    }

    public function testArrayFilter(): void
    {
        $numbers = [1, 2, 3, 4, 5, 6];
        $even = array_filter($numbers, fn($n) => $n % 2 === 0);
        $this->assertEquals([2, 4, 6], array_values($even));
    }

    public function testArrayMap(): void
    {
        $numbers = [1, 2, 3];
        $doubled = array_map(fn($n) => $n * 2, $numbers);
        $this->assertEquals([2, 4, 6], $doubled);
    }

    public function testArrayReduce(): void
    {
        $numbers = [1, 2, 3, 4, 5];
        $sum = array_reduce($numbers, fn($carry, $n) => $carry + $n, 0);
        $this->assertEquals(15, $sum);
    }

    public function testArrayUnique(): void
    {
        $numbers = [1, 2, 2, 3, 3, 3];
        $unique = array_unique($numbers);
        $this->assertCount(3, $unique);
    }

    public function testArrayReverse(): void
    {
        $numbers = [1, 2, 3];
        $this->assertEquals([3, 2, 1], array_reverse($numbers));
    }

    public function testArraySlice(): void
    {
        $numbers = [1, 2, 3, 4, 5];
        $this->assertEquals([2, 3], array_slice($numbers, 1, 2));
    }

    public function testArraySearch(): void
    {
        $fruits = ['apple', 'banana', 'cherry'];
        $this->assertEquals(1, array_search('banana', $fruits));
        $this->assertFalse(array_search('grape', $fruits));
    }

    public function testInArray(): void
    {
        $fruits = ['apple', 'banana', 'cherry'];
        $this->assertTrue(in_array('banana', $fruits));
        $this->assertFalse(in_array('grape', $fruits));
    }

    public function testArrayKeys(): void
    {
        $assoc = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertEquals(['a', 'b', 'c'], array_keys($assoc));
    }

    public function testArrayValues(): void
    {
        $assoc = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertEquals([1, 2, 3], array_values($assoc));
    }

    public function testArrayCombine(): void
    {
        $keys = ['a', 'b', 'c'];
        $values = [1, 2, 3];
        $this->assertEquals(['a' => 1, 'b' => 2, 'c' => 3], array_combine($keys, $values));
    }

    public function testArrayFlip(): void
    {
        $array = ['a' => 1, 'b' => 2];
        $this->assertEquals([1 => 'a', 2 => 'b'], array_flip($array));
    }

    public function testArraySort(): void
    {
        $numbers = [3, 1, 4, 1, 5, 9, 2, 6];
        sort($numbers);
        $this->assertEquals([1, 1, 2, 3, 4, 5, 6, 9], $numbers);
    }
}
