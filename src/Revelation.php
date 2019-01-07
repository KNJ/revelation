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

    public function bind(Closure $cl): Closure
    {
        $fn = $cl->bindTo($this->original, $this->original);

        return $fn;
    }

    public function getStatic(string $property)
    {
        $closure = function ($property) {
            return get_class($this)::${$property};
        };

        return $this->bind($closure)->__invoke($property);
    }

    public function setStatic(string $prop, $val)
    {
        $closure = function ($prop, $val) {
            get_class($this)::${$prop} = $val;
        };
        $this->bind($closure)->__invoke($prop, $val);
    }

    public function call(string $method, ...$args)
    {
        $self = $this;
        $closure = function ($method, $args) use ($self) {
            $return = $this->$method(...$args);

            if ($return === $this) {
                $return = $self;
            }

            return $return;
        };

        return $this->bind($closure)->__invoke($method, $args);
    }

    public function callStatic(string $method, ...$args)
    {
        $closure = function ($method, $args) {
            $return = get_class($this)::$method(...$args);

            return $return;
        };

        return $this->bind($closure)->__invoke($method, $args);
    }

    public function __get(string $prop)
    {
        $closure = function ($prop) {
            return $this->{$prop};
        };

        return $this->bind($closure)->__invoke($prop);
    }

    public function __set(string $prop, $val)
    {
        $closure = function ($prop, $val) {
            $this->{$prop} = $val;
        };
        $this->bind($closure)->__invoke($prop, $val);
    }

    public function __call(string $method, $args)
    {
        return $this->call($method, ...$args);
    }
}
