<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use BitMask\Util\Bits as BitUtils;
use Generator;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

use function log;

class GetSetBitsIndexBench
{
    private function getSetBitsIndex1(int $mask): array
    {
        if ($mask === PHP_INT_MAX) {
            $mask = PHP_INT_MAX / 8; // IDK why it stuck here, lower the number
        }
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
        return array_map(function ($bit) {
            return BitUtils::getMostSignificantBit($bit);
        }, BitUtils::getSetBits($mask));
    }

    private function getSetBitsIndex3(int $mask): array
    {
        $bitIndexes = [];
        for ($i = 0; $i < PHP_INT_SIZE * 8; $i++) {
            if (($mask & 1 << $i) !== 0) {
                $bitIndexes[] = $i;
            }
        }
        return $bitIndexes;
    }

    private function getSetBitsIndex4(int $mask): array
    {
        $bitIndexes = [];
        $i = 0;
        while ($mask > 0) {
            if (($mask & 1) !== 0) {
                $bitIndexes[] = $i;
            }
            $mask >>= 1;
            $i++;
        }
        return $bitIndexes;
    }

    private function getSetBitsIndex5(int $mask): array
    {
        $bitIndexes = [];
        while ($mask !== 0) {
            $lsb = $mask & -$mask;
            $index = (int) log($lsb, 2);
            $bitIndexes[] = $index;
            $mask ^= $lsb;
        }
        return $bitIndexes;
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
    #[Iterations(3)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex1(array $mask): void
    {
        $this->getSetBitsIndex1($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(3)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex2(array $mask): void
    {
        $this->getSetBitsIndex2($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(3)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex3(array $mask): void
    {
        $this->getSetBitsIndex3($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(3)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex4(array $mask): void
    {
        $this->getSetBitsIndex4($mask[0]);
    }

    /** @param int[] $mask */
    #[Revs(100000)]
    #[Iterations(3)]
    #[ParamProviders('maskProvider')]
    public function benchGetSetBitsIndex5(array $mask): void
    {
        $this->getSetBitsIndex5($mask[0]);
    }
}
