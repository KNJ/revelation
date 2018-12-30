<?php

namespace Tests;

class Mutable
{
    private $count = 0;

    private static $number = 0;

    public function increment()
    {
        $this->count++;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function changeSelfNumber($number)
    {
        self::$number = $number;
    }

    public function changeStaticNumber($number)
    {
        static::$number = $number;
    }
}
