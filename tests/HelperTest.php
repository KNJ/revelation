<?php

declare(strict_types=1);

namespace Tests;

use Wazly\Revelation;
use PHPUnit\Framework\TestCase;
use function Wazly\Revelation\reveal;

final class HelperTest extends TestCase
{
    public function testRevealFunctionReturnsRevelationObject()
    {
        $obj = new class {};
        $revealed = reveal($obj);
        $this->assertSame(Revelation::class, get_class($revealed));
    }
}
