<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use PHPUnit\Framework\TestCase;

final class MutableObjectTest extends TestCase
{
    public function setUp()
    {
        $this->obj = Revelation::wrap(new Mutable);
        $this->siblingObj = Revelation::wrap(new Mutable);
        $this->exObj = Revelation::wrap(new class extends Mutable {});
    }

    public function testGetPrivateStaticProperty()
    {
        $this->assertSame(0, $this->obj->getStatic('number'));
        $this->assertSame(0, $this->siblingObj->getStatic('number'));
    }

    public function testFailToGetPrivateStaticProperty()
    {
        $this->expectException(\Error::class);
        $this->exObj->getStatic('number');
    }

    public function testReadingPrivateProperty()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->assertSame($i, $this->obj->count);
            $this->obj->increment();
        }

        $this->assertSame($i, $this->obj->count);
    }

    /**
     * @depends testGetPrivateStaticProperty
     */
    public function testChangePrivateSelfNumber()
    {
        $number = 7;
        $this->obj->changeSelfNumber($number);
        $this->assertSame($number, $this->obj->getStatic('number'));
        $this->assertSame($number, $this->siblingObj->getStatic('number'));
    }

    /**
     * @depends testGetPrivateStaticProperty
     */
    public function testChangePrivateStaticNumber()
    {
        $number = 9;
        $this->obj->changeStaticNumber($number);
        $this->assertSame($number, $this->obj->getStatic('number'));
        $this->assertSame($number, $this->siblingObj->getStatic('number'));
    }
}
