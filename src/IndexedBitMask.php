<?php
declare(strict_types = 1);

namespace BitMask;

use BitMask\Util\Bits;

class IndexedBitMask extends BitMask
{
    protected $map;

    public function __construct(int $mask = 0)
    {
        $this->map = [];
        $this->set($mask);
    }

    public function set(int $mask = 0)
    {
        parent::set($mask);
        for ($index = 0; $index < Bits::getMSB($mask); $index++) {
            $this->map[$index] = $this->isSetBit(pow(2, $index));
        }
    }

    public function setBit(int $bit, bool $state = true)
    {
        parent::setBit($bit, $state);
        $index = Bits::bitToIndex($bit);
        $this->map[$index] = $state;
    }

    public function unsetBit(int $bit)
    {
        $this->setBit($bit, false);
    }

    public function getByIndex(int $index = 0) : ?bool
    {
        return isset($this->map[$index]) ? $this->map[$index] : null;
    }
}
