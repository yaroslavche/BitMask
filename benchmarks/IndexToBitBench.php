<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class IndexToBitBench
{
    public function indexProvider(): Generator
    {
        yield [1];
        yield [100];
        yield [10000];
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"indexProvider"})
     */
    public function benchIndexToBit1(array $index): void
    {
        $this->indexToBit1($index[0]);
    }

    /**
     * @Revs(100000)
     * @Iterations(5)
     * @ParamProviders({"indexProvider"})
     */
    public function benchIndexToBit2(array $index): void
    {
        $this->indexToBit2($index[0]);
    }

    private function indexToBit1(int $index): int
    {
        return (int)pow(2, $index);
    }

    private function indexToBit2(int $index): int
    {
        return 1 << $index;
    }
}
