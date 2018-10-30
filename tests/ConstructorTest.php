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
        $obj = Revelation::new(new Constructor('a', 'b'));
        $this->assertInstancesAndProperties($obj);

        return $obj;
    }

    public function testCreatingObjectRevelation()
    {
        $obj = Revelation::new(Constructor::class, 'a', 'b');
        $this->assertInstancesAndProperties($obj);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValueRevelation()
    {
        Revelation::new([], 'a', 'b');
    }

    /**
     * @depends           testCreatedObjectRevelation
     * @expectedException \InvalidArgumentException
     */
    public function testRevelationOfRevelation(RevelationInterface $obj)
    {
        Revelation::new($obj, 'a', 'b');
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
