<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use BitMask\Util\Bits;

final class BitMask implements BitMaskInterface
{
    public function __construct(
        private int $mask = 0,
        private readonly ?int $mostSignificantBit = null,
    ) {
        $this->checkMask($this->mask);
    }

    public function __toString(): string
    {
        return (string) $this->mask;
    }

    public function get(): int
    {
        return $this->mask;
    }

    /** @throws NotSingleBitException */
    public function set(int ...$bits): void
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        array_walk($bits, fn(int $bit) => $this->mask |= $bit);
    }

    /** @throws NotSingleBitException */
    public function remove(int ...$bits): void
    {
        foreach ($bits as $bit) {
            if ($this->has($bit)) {
                $this->mask &= ~$bit;
            }
        }
    }

    /** @throws NotSingleBitException */
    public function has(int ...$bits): bool
    {
        array_walk($bits, fn(int $bit) => $this->checkBit($bit));
        return !in_array(false, array_map(fn(int $bit) => ($this->mask & $bit) == $bit, $bits), true);
    }

    /** @throws OutOfRangeException */
    private function checkMask(int $mask): void
    {
        if (
            $mask < 0 ||
            null !== $this->mostSignificantBit && $mask >= Bits::indexToBit($this->mostSignificantBit + 1)
        ) {
            throw new OutOfRangeException((string) $mask);
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
            throw new NotSingleBitException((string) $bit);
        }
    }
}
