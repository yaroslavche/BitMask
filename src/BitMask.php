<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use BitMask\Util\Bits;

class BitMask implements BitMaskInterface
{
    public function __construct(
        protected int $mask = 0,
        private readonly ?int $mostSignificantBit = null,
    ) {
        $this->set($mask);
    }

    public function __toString(): string
    {
        return (string)$this->mask;
    }

    /** @inheritDoc */
    public function get(): int
    {
        return $this->mask;
    }

    /** @deprecated set would have variadic int args and expects only single bits */
    public function set(int $mask): void
    {
        $this->checkMask($mask);
        $this->mask = $mask;
    }

    /** @deprecated would be dropped */
    public function unset(): void
    {
        $this->mask = 0;
    }

    /** @deprecated would be dropped */
    public function isSet(int $mask): bool
    {
        return ($this->mask & $mask) === $mask;
    }

    /** @deprecated use set instead */
    public function setBits(int ...$bits): void
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        array_walk($bits, fn(int $bit) => $this->mask |= $bit);
    }

    /** @deprecated use remove instead */
    public function unsetBits(int ...$bits): void
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        array_walk($bits, fn(int $bit) => $this->mask ^= $bit);
        // $this->mask &= ~$bit;
    }

    /** @deprecated use has instead */
    public function isSetBits(int ...$bits): bool
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        return !in_array(false, array_map(fn(int $bit) => $this->isSet($bit), $bits), true);
    }

    /** @deprecated would be dropped */
    public function setBitByShiftOffset(int $shiftOffset): void
    {
        $this->setBits(1 << $shiftOffset);
    }

    /** @deprecated would be dropped */
    public function unsetBitByShiftOffset(int $shiftOffset): void
    {
        $this->unsetBits(1 << $shiftOffset);
    }

    /** @deprecated would be dropped */
    public function isSetBitByShiftOffset(int $shiftOffset): bool
    {
        return $this->isSetBits(1 << $shiftOffset);
    }

    /** @throws OutOfRangeException */
    private function checkMask(int $mask): void
    {
        if ($mask < 0 || $this->mostSignificantBit && $mask >= Bits::indexToBit($this->mostSignificantBit + 1)) {
            throw new OutOfRangeException((string)$mask);
        }
    }

    /**
     * @throws NotSingleBitException
     * @throws OutOfRangeException
     */
    private function checkBit(int $bit): void
    {
        $this->checkMask($bit);
        if (!Bits::isSingleBit($bit)) {
            throw new NotSingleBitException((string)$bit);
        }
    }
}
