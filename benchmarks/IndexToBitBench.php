<?php

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class IndexToBitBench
 */
class IndexToBitBench
{
    public function provideIndex()
    {
        yield [1];
        yield [100];
        yield [10000];
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"provideIndex"})
     */
    public function benchIndexToBit1($index)
    {
        $this->indexToBit1($index[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"provideIndex"})
     */
    public function benchIndexToBit2($index)
    {
        $this->indexToBit2($index[0]);
    }

    private function indexToBit1(int $index = 0): int
    {
        return (int)pow(2, $index);
    }

    private function indexToBit2(int $index = 0): int
    {
        return 1 << $index;
    }
}
