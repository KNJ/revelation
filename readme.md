# Revelation

[![Build Status](https://travis-ci.org/KNJ/revelation.svg?branch=master)](https://travis-ci.org/KNJ/revelation)
[![codecov](https://codecov.io/gh/KNJ/revelation/branch/master/graph/badge.svg)](https://codecov.io/gh/KNJ/revelation)

It is very hard to test a protected or private method when using testing tool like PHPUnit. With Revelation, you can access all the methods and properties by using a simple function.

## Supported Versions

- PHP 7.1 or higher

## Installation

Use Composer:

```
composer require --dev knj/revelation
```

## Quick Start

Try the code below (with autoload):

```php
<?php

use function Wazly\Revelation\reveal;

$obj = new class {
    private function do() {
        return 'You called private method successfully!';
    }
};

echo reveal($obj)->do();
```

## Usage

### Revelation object

Create a revelation object so that you can access non-public methods and properties.

```php
<?php

class Stuff
{
    private $privateProperty;

    public function __construct($a, $b)
    {
        $this->privateProperty = $a + $b;
    }

    private function privateMethod($x, $y)
    {
        $this->privateProperty = $x + $y;

        return $this;
    }
}
```

To create it with Stuff object, use `reveal()` helper function.

```php
<?php

use function Wazly\Revelation\reveal;

$stuff = new Stuff(1, 2);
$stuff = reveal($stuff);                // now $stuff is a Revelation object
echo $stuff->privateProperty; // 3
$stuff->privateMethod(1, 100);
echo $stuff->privateProperty; // 101
```

#### Ways to create a Revelation object

```php
// use function Wazly\Revelation\reveal;
reveal($stuff);
reveal(Stuff::class, 1, 2);
reveal(function ($a, $b) { return new Stuff($a, $b); }, 1, 2);

// use Wazly\Revelation;
Revelation::wrap($stuff);
Revelation::wrap(Stuff::class, 1, 2);
Revelation::wrap(function ($a, $b) { return new Stuff($a, $b); }, 1, 2);
```

#### Getting original object

`getOriginal()` retrieves reference to the original object.

```php
$stuff = reveal($stuff);
echo get_class($stuff);                // Wazly\Revelation
echo get_class($stuff->getOriginal()); // Stuff
```

#### Reference to the original object

Revelation objects created with the same object have the same reference to the original object.

```php
$rev1 = reveal($stuff);
$rev1->privateMethod(1, 2);

$rev2 = reveal($stuff);
$rev2->privateMethod(3, 4);

echo $rev1->privateProperty; // 7 not 3
echo $rev2->privateProperty; // 7
```

If you want different Revelation objects not to have the same reference, use `Revelation::clone()`.

```php
$rev1 = reveal($stuff);
$rev1->privateMethod(1, 2);

$rev2 = Revelation::clone($stuff);
$rev2->privateMethod(3, 4);

echo $rev1->privateProperty; // 3
echo $rev2->privateProperty; // 7
```

#### Method chaining

`return $this` never return the original object itself so that a Revelation object can chain methods.

```php
reveal($stuff)->privateMethod(1, 2)->privateMethod(3, 4);
```

#### Static method and property

`getStatic()` and `callStatic()` are available.

```php
class A
{
    protected static $staticProperty = 'static';

    protected static function className()
    {
        return __CLASS__;
    }

    protected static function selfName()
    {
        return self::className();
    }

    protected static function staticName()
    {
        return static::className();
    }
}

class B extends A
{
    protected static function className()
    {
        return __CLASS__;
    }
}

echo reveal(B::class)->getStatic('staticProperty'); // static
echo reveal(B::class)->callStatic('className');       // B
echo reveal(B::class)->callStatic('selfName');        // A
echo reveal(B::class)->callStatic('staticName');      // B
```
