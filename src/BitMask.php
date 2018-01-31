<?php

namespace BitMask;

class BitMask
{
    protected $storage;

    public function __construct()
    {
        $this->storage = 0;
    }

    public function get($bit)
    {
        return $this->storage & $bit;
    }

    public function set($bit)
    {
        $this->storage |= $bit;
    }

    public function unset($bit)
    {
        $flag = $this->get($bit);
        if ($flag) {
            $this->storage ^= $bit;
        }
    }

    public function clear()
    {
        $this->storage = 0;
    }

    public static function intToBit($int)
    {
        return (int)decbin($int);
    }

    public function getMask()
    {
        return $this->storage;
    }

    public function setMask(int $mask)
    {
        $this->storage = $mask;
    }

    public function getBits($decimal)
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
