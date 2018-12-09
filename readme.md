# Revelation

[![Build Status](https://travis-ci.org/KNJ/revelation.svg?branch=master)](https://travis-ci.org/KNJ/revelation)
[![codecov](https://codecov.io/gh/KNJ/revelation/branch/master/graph/badge.svg)](https://codecov.io/gh/KNJ/revelation)

It is very hard to test a protected or private method when using testing tool like PHPUnit. With Revelation, you can access all the methods and properties by using a simple function.

## Requirements

- PHP 7.1 or higher

## Installation

Use Composer.

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
