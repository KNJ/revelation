<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use PHPUnit\Framework\TestCase;

final class MmutableObjectTest extends TestCase
{
    public function setUp()
    {
        $this->obj = Revelation::new(new Mutable);
    }

    public function testReadingPrivateProperty()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->assertSame($i, $this->obj->count);
            $this->obj->increment();
        }

        $this->assertSame($i, $this->obj->count);
    }
}
