<?php

namespace Tamiroh\PhpDataclass\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Tamiroh\PhpDataclass\Test\Data\Human2;

class WriteTest extends TestCase
{
    public function test1(): void
    {
        $name = 'John';
        $age = 22;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Dynamically declaring properties is prohibited');

        $human = new Human2([
            'name' => $name,
            'age' => $age,
        ]);

        $human->Name = 'Mike';
    }

    public function test2(): void
    {
        $name = 'John';
        $age = 22;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Dynamically declaring properties is prohibited');

        $human = new Human2([
            'name' => $name,
            'age' => $age,
        ]);

        $human->alice = 'dummy';
    }
}