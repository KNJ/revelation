# Revelation

[![Build Status](https://travis-ci.org/KNJ/revelation.svg?branch=master)](https://travis-ci.org/KNJ/revelation)
[![codecov](https://codecov.io/gh/KNJ/revelation/branch/master/graph/badge.svg)](https://codecov.io/gh/KNJ/revelation)

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
