<?php

namespace Tamiroh\PhpDataclass\Test;

use PHPUnit\Framework\TestCase;
use Tamiroh\PhpDataclass\Test\Data\Human2;
use TypeError;
use UnexpectedValueException;

class CreateTest extends TestCase
{

    public function test1(): void
    {
        $name = 'John';
        $age = null;

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Cannot assign null to property Tamiroh\PhpDataclass\Test\Data\Human2::$age of type int');

        new Human2([
            'name' => $name,
            'age' => $age,
        ]);
    }

    public function test2(): void
    {
        $name = 'John';

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('$age is not initialized even though it is non-null');

        new Human2([
            'name' => $name,
        ]);
    }

    public function test3(): void
    {
        $name = 'John';
        $age = 22;

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('$Name is not defined in Tamiroh\PhpDataclass\Test\Data\Human2');

        new Human2([
            'Name' => $name,
            'age' => $age,
        ]);
    }
}