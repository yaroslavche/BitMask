<?php

use BitMask\Util\Bits as BitUtils;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class GetSetBitsIndexBench
 */
class GetSetBitsIndexBench
{
    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchGetSetBitsIndex1()
    {
        $mask = 1 << 64;
        $this->getSetBitsIndex1($mask);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchGetSetBitsIndex2()
    {
        $mask = 1 << 64;
        $this->getSetBitsIndex2($mask);
    }

    /**
     * @param int $mask
     * @return array
     * @throws Exception
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
