<?php

namespace BitMask;

class AssociativeBitMask extends IndexedBitMask
{
    protected $keys;

    public function __construct(array $keys = [])
    {
        parent::__construct();
        $this->keys = $keys;
        foreach ($keys as $index => $key) {
            $this->map[$index] = false;
        }
    }

    public function getByKey(string $key) : ?bool
    {
        $index = array_search($key, $this->keys);
        return $index >= 0 ? $this->map[$index] : null;
    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method) && strpos($method, 'is') === 0) {
            $key = lcfirst(substr($method, 2));
            return $this->getByKey($key);
        }
    }

    public function __get($key)
    {
        return $this->getByKey($key);
    }

    public function __set($key, bool $isSet)
    {
        $index = array_search($key, $this->keys);
        $bit = pow(2, $index);
        if ($isSet) {
            $this->set($bit);
        } else {
            $this->unset($bit);
        }
    }
}
