<?php
declare(strict_types = 1);

namespace BitMask;

use BitMask\Util\Bits;

class AssociativeBitMask extends IndexedBitMask
{
    protected $keys;

    public function __construct(array $keys, int $mask = 0)
    {
        if (empty($keys)) {
            throw new \Exception('Keys must be non empty');
        }
        $this->keys = $keys;
        $this->set($mask);
    }

    final public function set(int $mask = 0)
    {
        parent::set($mask);
        $keysCount = count($this->keys);
        $maxValue = pow(2, $keysCount) - 1;
        if ($mask > $maxValue) {
            throw new \Exception(sprintf('Invalid given mask "%d". Maximum value for %d keys is %d', $mask, $keysCount, $maxValue));
        }
        for ($index = 0; $index < $keysCount - 1; $index++) {
            if (!isset($this->map[$index])) {
                $this->map[$index] = false;
            }
        }
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
