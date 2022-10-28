<?php
declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use BitMask\Util\Bits as BitUtils;
use Generator;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class GetSetBitsIndexBench
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
    public function benchGetSetBitsIndex1(array $mask): void
    {
        $this->getSetBitsIndex1($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex2(array $mask): void
    {
        $this->getSetBitsIndex2($mask[0]);
    }

    private function getSetBitsIndex1(int $mask): array
    {
        $bitIndexes = [];
        $scan = 1;
        while ($mask >= $scan) {
            if ($mask & $scan) {
                $bitIndexes[] = BitUtils::bitToIndex($scan);
            }
            $scan <<= 1;
        }
        return $bitIndexes;
    }

    private function getSetBitsIndex2(int $mask): array
    {
        $bitIndexes = [];
        foreach (BitUtils::getSetBits($mask) as $index => $bit) {
            $bitIndexes[$index] = BitUtils::getMostSignificantBit($bit);
        }
        return $bitIndexes;
    }
}
