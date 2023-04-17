# PHP Dataclass

## Basic Usage

```php
<?php

class Human extends Data
{
    public int $age;
    public int $weight;
    public int $height;
    public string $name;
    public ?string $address;

    public function greeting(): string
    {
        return "Hello, my name is {$this->name}!";
    }
}

$human = new Human([
    'age' => 22,
    'weight' => 51,
    'height' => 172,
    'name' => 'John',
    'address' => '1600 Pennsylvania Avenue NW Washington, D.C. 20500 U.S.'
]);
```