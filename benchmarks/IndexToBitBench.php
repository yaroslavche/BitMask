<?php

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class IndexToBitBench
 */
class IndexToBitBench
{
    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIndexToBit1()
    {
        $index = 1;
        $this->indexToBit1($index);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIndexToBit2()
    {
        $index = 1;
        $this->indexToBit2($index);
    }

    private function indexToBit1(int $index = 0): int
    {
        return pow(2, $index);
    }

    private function indexToBit2(int $index = 0): int
    {
        return 1 << $index;
    }
}
