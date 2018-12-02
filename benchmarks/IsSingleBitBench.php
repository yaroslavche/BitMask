<?php

use BitMask\Util\Bits as BitUtils;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class IsSingleBitBench
 */
class IsSingleBitBench
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
    public function benchIsSingleBit1($mask)
    {
        $this->isSingleBit1($mask[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"provideMask"})
     */
    public function benchIsSingleBit2($mask)
    {
        $this->isSingleBit2($mask[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"provideMask"})
     */
    public function benchIsSingleBit3($mask)
    {
        $this->isSingleBit3($mask[0]);
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
