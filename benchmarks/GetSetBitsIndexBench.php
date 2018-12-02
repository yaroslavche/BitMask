<?php

use BitMask\Util\Bits as BitUtils;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class GetSetBitsIndexBench
 */
class GetSetBitsIndexBench
{
    public function provideMask()
    {
        yield [1];
        yield [1 << 5];
        yield [1 << 16];
        yield [1 << 32];
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"provideMask"})
     */
    public function benchGetSetBitsIndex1($mask)
    {
        $this->getSetBitsIndex1($mask[0]);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @ParamProviders({"provideMask"})
     */
    public function benchGetSetBitsIndex2($mask)
    {
        $this->getSetBitsIndex2($mask[0]);
    }

    /**
     * @param int $mask
     * @return array
     * @throws Exception
     * @ParamProviders({"provideMask"})
     */
    private function getSetBitsIndex1(int $mask = 0): array
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

    /**
     * @param int $mask
     * @return array
     */
    private function getSetBitsIndex2(int $mask = 0): array
    {
        $bitIndexes = [];
        foreach (BitUtils::getSetBits($mask) as $index => $bit) {
            $bitIndexes[$index] = (int)log($bit, 2);
        }
        return $bitIndexes;
    }
}
