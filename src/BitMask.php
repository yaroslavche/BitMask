<?php

namespace BitMask;

/**
 * Base BitMask class
 */
class BitMask
{
    /**
     * bitmask value
     * @var int
     */
    protected $storage;

    /**
     * constructor
     *
     * @param int $mask = 0
     */
    public function __construct(int $mask = 0)
    {
        $this->set($mask);
    }

    /**
     * static constructor
     *
     * @param  integer $mask = 0
     * @return BitMask
     */
    public static function init(int $mask = 0) : BitMask
    {
        return new static($mask);
    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new \Exception('unknown method ' . $method);
        }
    }

    /**
     * get bitmask
     *
     * @return int
     */
    public function get() : int
    {
        return $this->storage;
    }

    /**
     * set bitmask
     * @param int $mask
     * @return void
     */
    public function set(int $mask)
    {
        $this->storage = $mask;
    }

    /**
     * set bitmask to 0
     * @return void
     */
    public function unset()
    {
        $this->storage = 0;
    }

    /**
     * check if given mask is set
     *
     * @param  int $mask
     * @return bool
     */
    public function isSet(int $mask) : bool
    {
        $set = $this->storage & $mask;
        return $set > 0;
    }

    /**
     * set given bit in bitmask
     *
     * @param [type] $bit [description]
     * @return void
     */
    public function setBit(int $bit)
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        $this->storage |= $bit;
    }

    /**
     * unset given bit in bitmask
     *
     * @param int $bit
     * @return void
     */
    public function unsetBit(int $bit)
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        if ($this->isSetBit($bit)) {
            $this->storage ^= $bit;
        }
    }

    public function unsetBit2(int $bit)
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        if ($this->isSetBit($bit)) {
            $this->storage &= ~$bit;
        }
    }

    /**
     * check if given bit is set
     *
     * @param int $bit
     * @return bool
     */
    public function isSetBit(int $bit) : bool
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        $set = $this->storage & $bit;
        return $set > 0;
    }
}
