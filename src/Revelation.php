<?php

namespace Wazly;

class Revelation
{
    public static function new($obj)
    {
        return new static($obj);
    }

    public function __construct($original)
    {
        $this->original = $original;
    }

    public function __get($prop)
    {
        $closure = function ($prop) {
            return $this->$prop;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($prop);
    }

    public function __call($method, $args)
    {
        $self = $this;
        $closure = function ($original, $method, $args) use ($self) {
            $return = $original->$method(...$args);

            if ($return === $this) {
                $return = $self;
            }

            return $return;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($this->original, $method, $args);
    }
}
