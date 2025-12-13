<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class MostSignificantBitBench
{
    private function isMostSignificantBit1(int $mask): int
    {
        $position = 0;
        while ($mask > 1) {
            $mask >>= 1;
            $position++;
        }
        return $position;
    }

    private function isMostSignificantBit2(int $mask): int
    {
        return (int) log($mask, 2);
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
    #[Revs(1000000)]
    #[ParamProviders('maskProvider')]
    public function benchMostSignificantBit1(array $mask): void
    {
        $result = $this->isMostSignificantBit1($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(1000000)]
    #[ParamProviders('maskProvider')]
    public function benchMostSignificantBit2(array $mask): void
    {
        $result = $this->isMostSignificantBit2($mask[0]);
    }
}
