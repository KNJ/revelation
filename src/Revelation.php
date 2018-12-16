<?php

namespace Wazly;

use InvalidArgumentException;

class Revelation implements RevelationInterface
{
    protected $original;

    public static function wrap($obj, ...$args): RevelationInterface
    {
        return new static($obj, ...$args);
    }

    public static function clone($obj): RevelationInterface
    {
        return new static(clone $obj);
    }

    public function __construct($original, ...$args)
    {
        if ($original instanceof RevelationInterface) {
            throw new InvalidArgumentException('Argument 1 must not be an instance of RevelationInterface.');
        } elseif (is_object($original)) {
            $this->original = $original;
        } elseif (is_string($original)) {
            $this->original = new $original(...$args);
        } else {
            throw new InvalidArgumentException('Argument 1 must be a type of object or string.');
        }
    }

    public function getOriginal(): object
    {
        return $this->original;
    }

    public function getStatic(string $property)
    {
        $closure = function ($original, $property) {
            return get_class($original)::$$property;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($this->original, $property);
    }

    public function doStatic(string $method, ...$args)
    {
        $self = $this;
        $closure = function ($original, $method, $args) use ($self) {
            $return = get_class($original)::$method(...$args);

            if ($return === $this) {
                $return = $self;
            }

            return $return;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($this->original, $method, $args);
    }

    public function __get(string $prop)
    {
        $closure = function ($prop) {
            return $this->$prop;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($prop);
    }

    public function __call(string $method, $args)
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
