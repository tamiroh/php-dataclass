<?php

namespace Tamiroh\PhpDataclass\Test\Data;

use DateTime;
use Tamiroh\PhpDataclass\Data;

final class Human4 extends Data
{
    public string $name;
    public int $age;
    public array $favorites;
    public DateTime $birthday;
};