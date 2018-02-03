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
        if ($mask > 0) {
            for ($index = 0; $index <= Bits::getMSB($mask); $index++) {
                $this->map[$index] = $this->isSetBit(pow(2, $index));
            }
        }
    }

    public function getByIndex(int $index = 0) : ?bool
    {
        return isset($this->map[$index]) ? $this->map[$index] : null;
    }

    public function setBit($bit)
    {
        parent::setBit($bit);
        $index = $this->getBitIndex($bit);
        if (!is_null($index)) {
            $this->map[$index] = true;
        }
    }

    public function unsetBit($bit)
    {
        $index = $this->getBitIndex($bit);
        if (!is_null($index)) {
            $this->map[$index] = false;
        }
        parent::unsetBit($bit);
    }

    public function getBitIndex($bit) : ?int
    {
        $index = log($bit, 2);
        if ($index >= 0) {
            return (int)$index;
        }
        return null;
    }

    public function set(int $mask = 0)
    {
        parent::set($mask);
        foreach (Util\Bits::getSetBits($mask) as $bit) {
            $index = $this->getBitIndex($bit);
            if (!is_null($index)) {
                $this->map[$index] = true;
            }
        }
    }
}
