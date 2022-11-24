<?php

namespace Tamiroh\PhpDataclass\Test\Data;

use DateTime;
use Tamiroh\PhpDataclass\Data;

final class Human5 extends Data
{
    public string $name = 'John';
    public int $age = 23;
    public array $favorites = ['banana', 'orange'];
    public DateTime $birthday;
};