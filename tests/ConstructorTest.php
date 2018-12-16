<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use Wazly\RevelationInterface;
use PHPUnit\Framework\TestCase;

final class ConstructorTest extends TestCase
{
    public function testCreatedObjectRevelation()
    {
        $obj = Revelation::wrap(new Constructor('a', 'b'));
        $this->assertInstancesAndProperties($obj);

        return $obj;
    }

    public function testCreatingObjectRevelation()
    {
        $obj = Revelation::wrap(Constructor::class, 'a', 'b');
        $this->assertInstancesAndProperties($obj);
    }

    public function testClosureRevelation()
    {
        $obj = Revelation::wrap(function ($x, $y) { return new Constructor($x, $y); }, 'a', 'b');
        $this->assertInstancesAndProperties($obj);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValueRevelation()
    {
        Revelation::wrap([], 'a', 'b');
    }

    /**
     * @depends           testCreatedObjectRevelation
     * @expectedException \InvalidArgumentException
     */
    public function testRevelationOfRevelation(RevelationInterface $obj)
    {
        Revelation::wrap($obj, 'a', 'b');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testClosureReturnsVoid()
    {
        Revelation::wrap(function ($x, $y) { new Constructor($x, $y); }, 'a', 'b');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testClosureReturnsArray()
    {
        Revelation::wrap(function ($x, $y) { return [$x, $y]; }, 'a', 'b');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testClosureReturnsRevelation()
    {
        Revelation::wrap(function ($x, $y) { return Revelation::wrap(new Constructor($x, $y)); }, 'a', 'b');
    }

    private function assertInstancesAndProperties($obj)
    {
        $this->assertInstanceOf(Revelation::class, $obj);
        $original = $obj->getOriginal();
        $this->assertInstanceOf(Constructor::class, $original);
        $this->assertSame('a', $original->a);
        $this->assertSame('b', $original->b);
    }
}
