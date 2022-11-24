<?php

namespace Tamiroh\PhpDataclass\Test;

use PHPUnit\Framework\TestCase;
use Tamiroh\PhpDataclass\Test\Data\Human2;

class ReadTest extends TestCase
{
    public function test1(): void
    {
        $name = 'John';
        $age = 22;

        $human = new Human2([
            'name' => $name,
            'age' => $age,
        ]);

        $this->assertSame($human->name, $name);
        $this->assertSame($human->age, $age);
    }
}