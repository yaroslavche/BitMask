<?php

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class UnsetBitBench
 *
 * @BeforeMethods({"init"})
 */
class UnsetBitBench
{
    private $storage;

    public function init()
    {
        $this->storage = 0;
    }

    public function provideBit()
    {
        yield [1];
        yield [1 << 32];
        yield [PHP_INT_MAX];
    }

    /**
     * @Revs(200000)
     * @Iterations(10)
     * @ParamProviders({"provideBit"})
     * @throws Exception
     */
    public function benchUnsetBit1($bit)
    {
        $this->unsetBit1($bit[0], false);
    }

    /**
     * @Revs(200000)
     * @Iterations(10)
     * @ParamProviders({"provideBit"})
     */
    public function benchUnsetBit2($bit)
    {
        $this->unsetBit2($bit[0], false);
    }

    /**
     * @param int $bit
     * @return void
     */
    private function unsetBit1(int $bit = 0)
    {
        $this->storage ^= $bit;
    }

    /**
     * @param int $bit
     * @return void
     */
    private function unsetBit2(int $bit = 0)
    {
        $this->storage &= ~$bit;
    }
}
