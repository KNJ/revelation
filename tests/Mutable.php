<?php

namespace Tests;

class Mutable
{
    private $count = 0;

    public function increment()
    {
        $this->count++;
    }
}
