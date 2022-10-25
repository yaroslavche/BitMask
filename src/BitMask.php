<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use BitMask\Util\Bits;

class BitMask implements BitMaskInterface
{
    /** @inheritDoc */
    public function __construct(
        private ?int $mask = null,
        private readonly ?int $bitsCount = null
    ) {
        if (!is_null($mask)) {
            $this->set($mask);
        }
    }

    public function __toString(): string
    {
        return (string)$this->mask;
    }

    public function __invoke(int $mask): bool
    {
        return $this->isSet($mask);
    }

    public static function init(?int $mask = null): BitMaskInterface
    {
        return new static($mask);
    }

    /** @inheritDoc */
    public function get(): ?int
    {
        return $this->mask;
    }

    /** @inheritDoc */
    public function set(int $mask): void
    {
        if ($mask < 0 || (!is_null($this->bitsCount) && $mask >= $this->bitsCount ** 2)) {
            throw new OutOfRangeException((string)$mask);
        }
        $this->mask = $mask;
    }

    /** @inheritDoc */
    public function unset(): void
    {
        $this->mask = null;
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
        if (!is_null($this->bitsCount) && $bit >= $this->bitsCount ** 2) {
            throw new OutOfRangeException((string)$bit);
        }
    }

    /** @inheritDoc */
    public function setBit(int $bit): void
    {
        $this->checkBit($bit);
        $this->mask |= $bit;
    }

    /** @inheritDoc */
    public function unsetBit(int $bit): void
    {
        $this->checkBit($bit);
        $this->mask ^= $bit;
//        $this->mask &= ~$bit;
    }

    /** @inheritDoc */
    public function isSetBit(int $bit): bool
    {
        $this->checkBit($bit);
        return $this->isSet($bit);
    }

    private function checkShiftOffset(int $shiftOffset): void
    {
        if ($shiftOffset < 0 || (null !== $this->bitsCount && $this->bitsCount <= $shiftOffset)) {
            throw new OutOfRangeException((string)$shiftOffset);
        }
    }

    /** @inheritDoc */
    public function setBitByShiftOffset(int $shiftOffset): void
    {
        $this->checkShiftOffset($shiftOffset);
        $bit = 1 << $shiftOffset;
        $this->setBit($bit);
    }

    /** @inheritDoc */
    public function unsetBitByShiftOffset(int $shiftOffset): void
    {
        $this->checkShiftOffset($shiftOffset);
        $bit = 1 << $shiftOffset;
        $this->unsetBit($bit);
    }

    /** @inheritDoc */
    public function isSetBitByShiftOffset(int $shiftOffset): bool
    {
        $this->checkShiftOffset($shiftOffset);
        $bit = 1 << $shiftOffset;
        return $this->isSetBit($bit);
    }
}
