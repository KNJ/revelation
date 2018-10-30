# Revelation

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
