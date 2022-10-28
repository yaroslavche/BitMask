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
        private ?int $mostSignificantBit = null,
    ) {
        $this->mostSignificantBit ??= Bits::getMostSignificantBit(PHP_INT_MAX);
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

    /** @inheritDoc */
    public function set(int $mask): void
    {
        if ($mask < 0 || ($mask >= $this->mostSignificantBit ** 2)) {
            throw new OutOfRangeException((string)$mask);
        }
        $this->mask = $mask;
    }

    /** @inheritDoc */
    public function unset(): void
    {
        $this->mask = 0;
    }

    /** @inheritDoc */
    public function isSet(int $mask): bool
    {
        return ($this->mask & $mask) === $mask;
    }

    /**
     * @throws NotSingleBitException
     * @throws OutOfRangeException
     */
    private function checkBit(int $bit): void
    {
        if (!Bits::isSingleBit($bit)) {
            throw new NotSingleBitException((string)$bit);
        }
        if ($bit >= $this->mostSignificantBit ** 2) {
//            var_dump($bit, $this->mostSignificantBit, $this->mostSignificantBit ** 2);
            throw new OutOfRangeException((string)$bit);
        }
    }

    /** @inheritDoc */
    public function setBits(int ...$bits): void
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        array_walk($bits, fn(int $bit) => $this->mask |= $bit);
    }

    /** @inheritDoc */
    public function unsetBits(int ...$bits): void
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        array_walk($bits, fn(int $bit) => $this->mask ^= $bit);
//        $this->mask &= ~$bit;
    }

    /** @inheritDoc */
    public function isSetBits(int ...$bits): bool
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        return !in_array(false, array_map(fn(int $bit) => $this->isSet($bit), $bits), true);
    }

    /** @inheritDoc */
    public function setBitByShiftOffset(int $shiftOffset): void
    {
        $this->setBits(1 << $shiftOffset);
    }

    /** @inheritDoc */
    public function unsetBitByShiftOffset(int $shiftOffset): void
    {
        $this->unsetBits(1 << $shiftOffset);
    }

    /** @inheritDoc */
    public function isSetBitByShiftOffset(int $shiftOffset): bool
    {
        return $this->isSetBits(1 << $shiftOffset);
    }
}
