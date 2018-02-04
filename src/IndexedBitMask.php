<?php

namespace BitMask;

use BitMask\Util\Bits;

class IndexedBitMask extends BitMask
{
    protected $map;

    public function __construct(int $mask = 0)
    {
        parent::__construct($mask);
        $this->map = [];
        $this->set($mask);
    }

    public function getByIndex(int $index = 0) : ?bool
    {
        return isset($this->map[$index]) ? $this->map[$index] : null;
    }

    public function setBit(int $bit)
    {
        parent::setBit($bit);
        $index = Bits::bitToIndex($bit);
        $this->map[$index] = true;
    }

    public function unsetBit(int $bit)
    {
        parent::unsetBit($bit);
        $index = Bits::bitToIndex($bit);
        $this->map[$index] = false;
    }

    public function set(int $mask = 0)
    {
        parent::set($mask);
        if ($mask > 0) {
            for ($index = 0; $index <= Bits::getMSB($mask); $index++) {
                $this->map[$index] = $this->isSetBit(pow(2, $index));
            }
        }
    }
}
