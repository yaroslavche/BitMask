<?php

namespace BitMask;

class IndexedBitMask extends BitMask
{
    protected $map;

    public function __construct()
    {
        parent::__construct();
        $this->map = [];
    }

    public function getByIndex(int $index = 0) : ?bool
    {
        return isset($this->map[$index]) ? $this->map[$index] : null;
    }

    public function set($bit)
    {
        parent::set($bit);
        $index = $this->getBitIndex($bit);
        if (!is_null($index)) {
            $this->map[$index] = true;
        }
    }

    public function unset($bit)
    {
        $index = $this->getBitIndex($bit);
        if (!is_null($index)) {
            $this->map[$index] = false;
        }
        parent::unset($bit);
    }

    public function getBitIndex($bit) : ?int
    {
        $index = log($bit, 2);
        if ($index >= 0) {
            return (int)$index;
        }
        return null;
    }

    public function setMask(int $mask = 0)
    {
        parent::setMask($mask);
        foreach ($this->getBits($mask) as $bit) {
            $index = $this->getBitIndex($bit);
            if (!is_null($index)) {
                $this->map[$index] = true;
            }
        }
    }
}
