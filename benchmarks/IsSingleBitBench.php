<?php

use BitMask\Util\Bits as BitUtils;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class IsSingleBitBench
 */
class IsSingleBitBench
{
    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIsSingleBit1()
    {
        $mask = 1 << 64;
        $this->isSingleBit1($mask);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIsSingleBit2()
    {
        $mask = 1 << 64;
        $this->isSingleBit2($mask);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIsSingleBit3()
    {
        $mask = 1 << 64;
        $this->isSingleBit3($mask);
    }

    /**
     * @param int $mask
     * @return bool
     */
    private function isSingleBit1(int $mask = 0): bool
    {
        return count(BitUtils::getSetBits($mask)) === 1;
    }

    /**
     * @param int $mask
     * @return bool
     */
    private function isSingleBit2(int $mask = 0): bool
    {
        return pow(2, BitUtils::getMSB($mask)) === $mask;
    }

    /**
     * @param int $mask
     * @return bool
     */
    private function isSingleBit3(int $mask = 0): bool
    {
        $shift = BitUtils::getMSB($mask) - 1;
        if ($shift < 0) return false;
        return 1 << $shift === $mask;
    }
}
