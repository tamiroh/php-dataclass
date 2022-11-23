<?php

namespace Tamiroh\PhpDataclass\Test;

use PHPUnit\Framework\TestCase;
use Tamiroh\PhpDataclass\Test\Data\Human;
use TypeError;
use UnexpectedValueException;

class Test extends TestCase
{
    public function test1(): void
    {
        $name = 'John';
        $age = 22;

        $human = new Human([
            'name' => $name,
            'age' => $age,
        ]);

        $this->assertSame($human->name, $name);
        $this->assertSame($human->age, $age);
    }

    public function test2(): void
    {
        $name = 'John';
        $age = null;

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Cannot assign null to property Tamiroh\PhpDataclass\Test\Data\Human::$age of type int');

        new Human([
            'name' => $name,
            'age' => $age,
        ]);
    }

    public function test3(): void
    {
        $name = 'John';

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('$age is not initialized even though it is non-null');

        new Human([
            'name' => $name,
        ]);
    }
}