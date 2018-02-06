<?php
declare(strict_types = 1);

namespace BitMask;

class BitMask implements BitMaskInterface
{
    private $storage;

    public function __construct(int $mask = 0)
    {
        $this->set($mask);
    }

    public static function init(int $mask = 0)
    {
        return new static($mask);
    }

    public function get() : int
    {
        return $this->storage;
    }

    public function set(int $mask)
    {
        $this->storage = $mask;
    }

    public function unset()
    {
        $this->storage = 0;
    }

    public function isSet(int $mask) : bool
    {
        $set = $this->storage & $mask;
        return $set > 0;
    }

    public function setBit(int $bit, bool $state = true)
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        if ($state) {
            $this->storage |= $bit;
        } else {
            // $this->storage &= ~$bit;
            $this->storage ^= $bit;
        }
    }

    public function unsetBit(int $bit)
    {
        $this->setBit($bit, false);
    }

    public function isSetBit(int $bit) : bool
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \Exception('Must be single bit');
        }
        return $this->isSet($bit);
    }
}
