<?php
declare(strict_types=1);

namespace BitMask;

/**
 * Class AssociativeBitMask
 * @package BitMask
 */
class AssociativeBitMask extends IndexedBitMask implements \JsonSerializable
{
    /**
     * @var array $keys
     * @todo add type in 7.3
     */
    protected $keys;

    /**
     * AssociativeBitMask constructor.
     * @param array $keys
     * @param int $mask
     * @throws \Exception
     */
    public function __construct(array $keys, int $mask = 0)
    {
        if (empty($keys)) {
            throw new \Exception('Keys must be non empty');
        }
        $this->keys = $keys;
        parent::__construct($mask);
    }

    /**
     * @param int $mask
     * @throws \Exception
     */
    final public function set(int $mask = 0): void
    {
        $keysCount = count($this->keys);
        $maxValue = pow(2, $keysCount) - 1;
        if ($mask > $maxValue) {
            throw new \Exception(sprintf('Invalid given mask "%d". Maximum value for %d keys is %d', $mask, $keysCount, $maxValue));
        }
        parent::set($mask);
        for ($index = 0; $index < $keysCount - 1; $index++) {
            if (!isset($this->map[$index])) {
                $this->map[$index] = false;
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    final public function getByKey(string $key): bool
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new \Exception(sprintf('Unknown key "%s"', $key));
        }
        return $this->map[$index];
    }

    /**
     * @param $method
     * @param $args
     * @return bool
     * @throws \Exception
     * @todo: Warning: Missing return statement
     */
    final public function __call($method, $args)
    {
        if (!method_exists($this, $method) && strpos($method, 'is') === 0) {
            $key = lcfirst(substr($method, 2));
            return $this->getByKey($key);
        }
    }

    /**
     * @param $key
     * @return bool
     * @throws \Exception
     */
    final public function __get($key)
    {
        return $this->getByKey($key);
    }

    /**
     * @param $key
     * @param bool $isSet
     * @throws \Exception
     */
    final public function __set($key, bool $isSet)
    {
        $state = $this->getByKey($key);
        if ($state === $isSet) {
            return;
        }
        $index = array_search($key, $this->keys);
        // $bit = pow(2, $index);
        $bit = 1 << $index;
        if ($isSet) {
            $this->setBit($bit);
        } else {
            $this->unsetBit($bit);
        }
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    final public function __isset($key)
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new \Exception(sprintf('Unknown key "%s"', $key));
        }
        return $this->map[$index];
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $array = [];
        foreach ($this->keys as $index => $key) {
            $array[$key] = $this->map[$index];
        }
        return $array;
    }
}
