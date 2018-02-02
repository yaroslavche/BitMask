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
        if (!method_exists($method)) {
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
     * check if given bit is set
     *
     * @param  int $bit
     * @return bool
     */
    public function isSet(int $bit) : bool
    {
        return $this->storage & $bit === 1 ? true : false;
    }

    /**
     * set given bit in bitmask
     *
     * @param [type] $bit [description]
     * @return void
     */
    public function setBit(int $bit)
    {
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
        if ($this->isSet($bit)) {
            $this->storage ^= $bit;
            // $this->storage &= ~$bit;
        }
    }

    /**
     * clear bitmask
     * @return void
     */
    public function clear()
    {
        $this->storage = 0b0;
    }

    /**
     * Get set bits from decimal mask
     *  $bm->getSetBits(5); // => [1, 4]
     *
     * @todo move to utils? static, add isSingleBit(0b101) => false, isMask() - wrap !isSingleBit() but nonsence (maybe)
     *
     * @param  int $decimal
     * @return array
     */
    public function getSetBits(int $decimal)
    {
        $scan = 1;
        $result = [];
        while ($decimal >= $scan) {
            if ($decimal & $scan) {
                $result[] = $scan;
            }
            $scan <<= 1;
        }
        return $result;
    }
}
