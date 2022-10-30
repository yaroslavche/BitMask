<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

#[BeforeMethods('init')]
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

    /** @param int[] $bit */
    #[Revs(300000)]
    #[Iterations(1)]
    #[ParamProviders('bitProvider')]
    public function benchUnsetBit1(array $bit): void
    {
        $this->unsetBit1($bit[0]);
    }

    /** @param int[] $bit */
    #[Revs(300000)]
    #[Iterations(1)]
    #[ParamProviders('bitProvider')]
    public function benchUnsetBit2(array $bit): void
    {
        $this->unsetBit2($bit[0]);
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
