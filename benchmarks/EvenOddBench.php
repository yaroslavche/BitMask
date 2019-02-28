<?php

use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * Class EvenOddBench
 */
class EvenOddBench
{
    public function provideNumber()
    {
        yield [0];
        yield [1];
        yield [10 ** 15];
        yield [PHP_INT_MAX];
    }

    /**
     * @Revs(1000000)
     * @ParamProviders({"provideNumber"})
     */
    public function benchEvenOdd1($number)
    {
        $this->isEven1($number[0]);
        $this->isOdd1($number[0]);
    }

    /**
     * @Revs(1000000)
     * @ParamProviders({"provideNumber"})
     */
    public function benchEvenOdd2($number)
    {
        $this->isEven2($number[0]);
        $this->isOdd2($number[0]);
    }

    private function isEven1(int $number = 0): bool
    {
        return ($number & 1) === 0;
    }

    private function isOdd1(int $number = 0): bool
    {
        return ($number & 1) === 1;
    }

    private function isEven2(int $number = 0): bool
    {
        return ($number % 2) === 0;
    }

    private function isOdd2(int $number = 0): bool
    {
        return ($number % 2) === 1;
    }
}
