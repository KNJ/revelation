<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use Wazly\RevelationInterface;
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
        $this->assertInstanceOf(RevelationInterface::class, $this->obj->returnThis());
    }

    public function testSameReference()
    {
        $this->assertSame($this->original, Revelation::wrap($this->original)->getOriginal());
    }

    public function testCloneObject()
    {
        $this->assertNotSame($this->original, Revelation::clone($this->original)->getOriginal());
    }

    public function testStaticMethod()
    {
        $this->assertSame(13, $this->obj->callStatic('returnIncreasedValue', 3, 9));
    }

    public function testResolveClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->callStatic('returnClassName'));
    }

    public function testResolveSelfClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->callStatic('getSelfClassName'));
        $this->assertSame('Tests\\Immutable', $this->exObj->callStatic('getSelfClassName'));
    }

    public function testResolveStaticClassName()
    {
        $this->assertSame('Tests\\Immutable', $this->obj->callStatic('getStaticClassName'));
        $this->assertNotSame('Tests\\Immutable', $this->exObj->callStatic('getStaticClassName'));
    }
}
