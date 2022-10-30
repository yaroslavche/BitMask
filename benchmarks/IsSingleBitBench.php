<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use BitMask\Util\Bits;
use Generator;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class IsSingleBitBench
{
    public function maskProvider(): Generator
    {
        yield [1];
        yield [1 << 5];
        yield [1 << 16];
        yield [1 << 32];
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('maskProvider')]
    public function benchIsSingleBit1(array $mask): void
    {
        $this->isSingleBit1($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('maskProvider')]
    public function benchIsSingleBit2(array $mask): void
    {
        $this->isSingleBit2($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('maskProvider')]
    public function benchIsSingleBit3(array $mask): void
    {
        $this->isSingleBit3($mask[0]);
    }

    private function isSingleBit1(int $mask): bool
    {
        return count(Bits::getSetBits($mask)) === 1;
    }

    private function isSingleBit2(int $mask): bool
    {
        return pow(2, Bits::getMostSignificantBit($mask)) === $mask;
    }

    private function isSingleBit3(int $mask): bool
    {
        return 1 << Bits::getMostSignificantBit($mask) === $mask;
    }
}
