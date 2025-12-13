<?php

declare(strict_types=1);

namespace BitMask\Util;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;

final class Bits
{
    /**
     * get most significant bit position (right -> left, index)
     * @see benchmarks/MostSignificantBitBench.php
     * ./vendor/bin/phpbench run benchmarks/MostSignificantBitBench.php --report=default
     * @example 10001 -> 4, 0010 -> 1, 00100010 -> 5
     */
    public static function getMostSignificantBit(int $mask): int
    {
        return (int) log($mask, 2);
    }

    /**
     * get array of set bits
     * @return int[]
     * @example 10010 => [2, 16], 111 => [1, 2, 4]
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function getSetBits(int $mask): array
    {
        $bits = [];
        $tempMask = $mask;
        while ($tempMask > 0) {
            $lsb = $tempMask & (~$tempMask + 1);
            $bits[] = $lsb;
            $tempMask ^= $lsb;
        }
        return $bits;
    }

    /**
     * is given bit was single checked bit (msb === 1 && other === 0)
     * @example 1000 => true, 010100 => false, 0000100 => true
     * @see benchmarks/IsSingleBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IsSingleBitBench.php --report=default
     */
    public static function isSingleBit(int $mask): bool
    {
        return $mask !== 0 && ($mask & ($mask - 1)) === 0;
    }

    /**
     * single bit to index (left > right)
     * @throws NotSingleBitException
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function bitToIndex(int $mask): int
    {
        if (!self::isSingleBit($mask)) {
            throw new NotSingleBitException('Argument must be a single bit');
        }
        return self::getMostSignificantBit($mask);
    }

    /**
     * index to single bit
     * @throws OutOfRangeException
     * @see benchmarks/IndexToBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IndexToBitBench.php --report=default
     * @example 0 => 0b1 (1), 1 => 0b10 (2), 2 => 0b100 (4), ...
     */
    public static function indexToBit(int $index): int
    {
        if ($index < 0) {
            throw new OutOfRangeException((string) $index);
        }
        return 1 << $index;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public static function toString(int $mask): string
    {
        return decbin($mask);
    }

    /**
     * @return int[]
     * @see benchmarks/GetSetBitsIndexBench.php
     * ./vendor/bin/phpbench run benchmarks/GetSetBitsIndexBench.php --report=default
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function getSetBitsIndexes(int $mask): array
    {
        $bitIndexes = [];
        $i = 0;
        while ($mask > 0) {
            if (($mask & 1) !== 0) {
                $bitIndexes[] = $i;
            }
            $mask >>= 1;
            $i++;
        }
        return $bitIndexes;
    }

    /**
     * Bitwise-based check if given number is even
     * @see benchmarks/EvenOddBench.php
     * ./vendor/bin/phpbench run benchmarks/EvenOddBench.php --report=default
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function isEvenNumber(int $number): bool
    {
        return ($number & 1) === 0;
    }

    /**
     * Bitwise-based check if given number is odd
     * @see benchmarks/EvenOddBench.php
     * ./vendor/bin/phpbench run benchmarks/EvenOddBench.php --report=default
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function isOddNumber(int $number): bool
    {
        return ($number & 1) === 1;
    }
}
