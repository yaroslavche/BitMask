<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use BitMask\Util\Bits as BitUtils;
use Generator;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class IsSingleBitBench
{
    public function maskProvider(): Generator
    {
        yield [1];
        yield [1 << 5];
        yield [1 << 16];
        yield [1 << 32];
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"maskProvider"})
     */
    public function benchIsSingleBit1(array $mask): void
    {
        $this->isSingleBit1($mask[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"maskProvider"})
     */
    public function benchIsSingleBit2(array $mask): void
    {
        $this->isSingleBit2($mask[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"maskProvider"})
     */
    public function benchIsSingleBit3(array $mask): void
    {
        $this->isSingleBit3($mask[0]);
    }

    private function isSingleBit1(int $mask): bool
    {
        return count(BitUtils::getSetBits($mask)) === 1;
    }

    private function isSingleBit2(int $mask): bool
    {
        return pow(2, BitUtils::getMSB($mask)) === $mask;
    }

    private function isSingleBit3(int $mask): bool
    {
        $shift = BitUtils::getMSB($mask) - 1;
        if ($shift < 0) {
            return false;
        }
        return 1 << $shift === $mask;
    }
}
