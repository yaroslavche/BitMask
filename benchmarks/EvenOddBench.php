<?php

declare(strict_types=1);

namespace Yaroslavche\Benchmarks;

use Generator;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class EvenOddBench
{
    public function numberProvider(): Generator
    {
        yield [0];
        yield [1];
        yield [10 ** 15];
        yield [PHP_INT_MAX];
    }

    /** @param int[] $number */
    #[Revs(1000000)]
    #[ParamProviders('numberProvider')]
    public function benchEvenOdd1(array $number): void
    {
        $this->isEven1($number[0]);
        $this->isOdd1($number[0]);
    }

    /** @param int[] $number */
    #[Revs(1000000)]
    #[ParamProviders('numberProvider')]
    public function benchEvenOdd2(array $number): void
    {
        $this->isEven2($number[0]);
        $this->isOdd2($number[0]);
    }

    private function isEven1(int $number): bool
    {
        return ($number & 1) === 0;
    }

    private function isOdd1(int $number): bool
    {
        return ($number & 1) === 1;
    }

    private function isEven2(int $number): bool
    {
        return ($number % 2) === 0;
    }

    private function isOdd2(int $number): bool
    {
        return ($number % 2) === 1;
    }
}
