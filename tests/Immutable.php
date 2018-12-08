<?php

namespace Tests;

class Immutable
{
    private static function returnIncreasedValue(int $val1, int $val2)
    {
        return $val1 + $val2 + 1;
    }

    private function returnSameValue($val)
    {
        return $val;
    }

    private function returnThisObject()
    {
        return $this;
    }

    protected static function returnClassName()
    {
        return __CLASS__;
    }

    protected static function getStaticClassName()
    {
        return static::returnClassName();
    }

    protected static function getSelfClassName()
    {
        return self::returnClassName();
    }
}
