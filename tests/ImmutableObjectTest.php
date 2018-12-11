<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use PHPUnit\Framework\TestCase;

final class ImmutableObjectTest extends TestCase
{
    public function setUp()
    {
        $this->original = new Immutable;
        $this->obj = Revelation::wrap($this->original);
        $this->exObj = Revelation::wrap(new class extends Immutable {
            protected static function returnClassName()
            {
                return __CLASS__;
            }
        });
    }

    public function testSingleArgument()
    {
        $this->assertSame('testing', $this->obj->returnSameValue('testing'));
    }

    public function testReturnThis()
    {
        $this->assertSame($this->original, $this->obj->returnThisObject());
    }

    public function testStaticMethod()
    {
        $this->assertSame(13, $this->obj->doStatic('returnIncreasedValue', 3, 9));
    }

    public function testResolveClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->doStatic('returnClassName'));
    }

    public function testResolveSelfClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->doStatic('getSelfClassName'));
        $this->assertSame('Tests\\Immutable', $this->exObj->doStatic('getSelfClassName'));
    }

    public function testResolveStaticClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->doStatic('getStaticClassName'));
        $this->assertNotSame('Tests\\Immutable', $this->exObj->doStatic('getStaticClassName'));
    }
}
