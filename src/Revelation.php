<?php

namespace Wazly;

use Closure;
use InvalidArgumentException;
use UnexpectedValueException;

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
        } elseif ($original instanceof Closure) {
            $this->original = $original(...$args);

            if (! is_object($this->original)) {
                throw new UnexpectedValueException('Closure of argument 1 must return a type of object.');
            }

            if ($this->original instanceof RevelationInterface) {
                throw new UnexpectedValueException('Closure of argument 1 must not return a type of closure.');
            }
        } elseif (is_object($original)) {
            $this->original = $original;
        } elseif (is_string($original)) {
            $this->original = new $original(...$args);
        } else {
            throw new InvalidArgumentException('Argument 1 must be a type of object or string.');
        }
    }

    public function getOriginal()
    {
        return $this->original;
    }

    public function getStatic(string $property)
    {
        $closure = function ($property) {
            return get_class($this)::$$property;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($property);
    }

    public function callStatic(string $method, ...$args)
    {
        $closure = function ($method, $args) {
            $return = get_class($this)::$method(...$args);

            return $return;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($method, $args);
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
        $closure = function ($method, $args) use ($self) {
            $return = $this->$method(...$args);

            if ($return === $this) {
                $return = $self;
            }

            return $return;
        };
        $fn = $closure->bindTo($this->original, $this->original);

        return $fn($method, $args);
    }
}
