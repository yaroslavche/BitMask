<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * @BeforeMethods({"init"})
 */
class UnsetBitBench
{
    private int $storage;

    public function init(): void
    {
        $this->storage = 0;
    }

    public function bitProvider(): Generator
    {
        yield [1];
        yield [1 << 32];
        yield [PHP_INT_MAX];
    }

    /**
     * @Revs(300000)
     * @Iterations(1)
     * @ParamProviders({"bitProvider"})
     */
    public function benchUnsetBit1(array $bit): void
    {
        $this->unsetBit1($bit[0], false);
    }

    /**
     * @Revs(300000)
     * @Iterations(1)
     * @ParamProviders({"bitProvider"})
     */
    public function benchUnsetBit2(array $bit): void
    {
        $this->unsetBit2($bit[0], false);
    }

    private function unsetBit1(int $bit): void
    {
        $this->storage ^= $bit;
    }

    private function unsetBit2(int $bit): void
    {
        $this->storage &= ~$bit;
    }
}
