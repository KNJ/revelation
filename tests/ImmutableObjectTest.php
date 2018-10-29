<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use PHPUnit\Framework\TestCase;

final class ImmutableObjectTest extends TestCase
{
    public function setUp()
    {
        $this->obj = Revelation::new(new Immutable);
    }

    public function testSingleArgument()
    {
        $this->assertSame('testing', $this->obj->reflectSingleValue('testing'));
    }
}
