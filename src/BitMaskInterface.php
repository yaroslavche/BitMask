<?php
declare(strict_types = 1);

namespace BitMask;

interface BitMaskInterface
{
    public function get() : int;
    public function set(int $mask);
    public function unset();
    public function isSet(int $mask) : bool;

    public function setBit(int $bit, bool $state = true);
    public function unsetBit(int $bit);
    public function isSetBit(int $bit) : bool;
}
