<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class IndexToBitBench
{
    public function indexProvider(): Generator
    {
        yield [1];
        yield [100];
        yield [10000];
    }

    /** @param int[] $index */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('indexProvider')]
    public function benchIndexToBit1(array $index): void
    {
        $this->indexToBit1($index[0]);
    }

    /** @param int[] $index */
    #[Revs(100000)]
    #[Iterations(5)]
    #[ParamProviders('indexProvider')]
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
