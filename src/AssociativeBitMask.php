<?php

namespace BitMask;

use BitMask\Util\Bits;

class AssociativeBitMask extends IndexedBitMask
{
    protected $keys;

    public function __construct(array $keys = [], int $mask = 0)
    {
        if (empty($keys)) {
            throw new \Exception('Keys must be non empty');
        }
        $this->keys = $keys;
        parent::__construct($mask);
    }

    final public function getByKey(string $key) : bool
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new \Exception(sprintf('Unknown key "%s"', $key));
        }
        return $this->map[$index];
    }

    final public function __call($method, $args)
    {
        if (!method_exists($this, $method) && strpos($method, 'is') === 0) {
            $key = lcfirst(substr($method, 2));
            return $this->getByKey($key);
        }
    }

    final public function __get($key)
    {
        return $this->getByKey($key);
    }

    final public function __set($key, bool $isSet)
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new \Exception(sprintf('Unknown key "%s"', $key));
        }
        // $bit = pow(2, $index);
        $bit = 1 << $index;
        if ($isSet) {
            $this->setBit($bit);
        } else {
            $this->unsetBit($bit);
        }
    }
}
