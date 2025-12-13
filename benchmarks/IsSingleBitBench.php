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

    private function isSingleBit4(int $mask): bool
    {
        return $mask !== 0 && ($mask & ($mask - 1)) === 0;
    }

    public function maskProvider(): Generator
    {
        yield [PHP_INT_MIN];
        yield [-1];
        yield [0];
        yield [1];
        yield [10 ** 15];
        yield [PHP_INT_MAX];
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

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('maskProvider')]
    public function benchIsSingleBit4(array $mask): void
    {
        $this->isSingleBit4($mask[0]);
    }
}
