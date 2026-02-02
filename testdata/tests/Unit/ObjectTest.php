<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use stdClass;

class ObjectTest extends TestCase
{
    public function testStdClass(): void
    {
        $obj = new stdClass();
        $obj->name = 'John';
        $obj->age = 30;

        $this->assertEquals('John', $obj->name);
        $this->assertEquals(30, $obj->age);
    }

    public function testObjectToArray(): void
    {
        $obj = new stdClass();
        $obj->a = 1;
        $obj->b = 2;

        $array = (array) $obj;
        $this->assertEquals(['a' => 1, 'b' => 2], $array);
    }

    public function testArrayToObject(): void
    {
        $array = ['name' => 'John', 'age' => 30];
        $obj = (object) $array;

        $this->assertEquals('John', $obj->name);
    }

    public function testPropertyExists(): void
    {
        $obj = new stdClass();
        $obj->name = 'John';

        $this->assertTrue(property_exists($obj, 'name'));
        $this->assertFalse(property_exists($obj, 'email'));
    }

    public function testGetObjectVars(): void
    {
        $obj = new stdClass();
        $obj->a = 1;
        $obj->b = 2;

        $vars = get_object_vars($obj);
        $this->assertEquals(['a' => 1, 'b' => 2], $vars);
    }

    public function testGetClass(): void
    {
        $obj = new stdClass();
        $this->assertEquals('stdClass', get_class($obj));
    }

    public function testIsObject(): void
    {
        $this->assertTrue(is_object(new stdClass()));
        $this->assertFalse(is_object(['array']));
    }

    public function testClone(): void
    {
        $obj1 = new stdClass();
        $obj1->value = 'original';

        $obj2 = clone $obj1;
        $obj2->value = 'cloned';

        $this->assertEquals('original', $obj1->value);
        $this->assertEquals('cloned', $obj2->value);
    }

    public function testInstanceOf(): void
    {
        $obj = new \ArrayIterator([]);

        $this->assertInstanceOf(\ArrayIterator::class, $obj);
        $this->assertInstanceOf(\Iterator::class, $obj);
    }

    public function testAnonymousClass(): void
    {
        $obj = new class {
            public function greet(): string
            {
                return 'Hello';
            }
        };

        $this->assertEquals('Hello', $obj->greet());
    }
}
