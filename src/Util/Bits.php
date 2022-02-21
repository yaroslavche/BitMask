<?php

declare(strict_types=1);

namespace BitMask\Util;

use BitMask\Exception\InvalidIndexException;
use BitMask\Exception\NotSingleBitException;

final class Bits
{

    /**
     * get most significant bit position (right -> left)
     * @example 10001 -> 5, 0010 -> 2, 00100010 -> 6
     * @todo research and use (if needed) https://www.geeksforgeeks.org/find-significant-set-bit-number/
     */
    public static function getMostSignificantBit(int $mask): int
    {
        $scan = 1;
        $mostSignificantBit = 0;
        while ($mask >= $scan) {
            $mostSignificantBit++;
            $scan <<= 1;
        }
        return $mostSignificantBit;
    }

    public static function getMSB(int $mask): int
    {
        return self::getMostSignificantBit($mask);
    }

    /**
     * get array of set bits
     * @example 10010 => [2, 16], 111 => [1, 2, 4]
     * @return int[]
     */
    public static function getSetBits(int $mask): array
    {
        $bits = [];
        $scan = 1;
        while ($mask >= $scan) {
            if ($mask & $scan) {
                $bits[] = $scan;
            }
            $scan <<= 1;
        }
        return $bits;
    }

    /**
     * is given bit was single checked bit (msb === 1 && other === 0)
     * @example 1000 => true, 010100 => false, 0000100 => true
     * @see benchmarks/IsSingleBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IsSingleBitBench.php --report=default
     *
     * @todo research maybe getMSB must return shift offset and then isSingleBit3 might be faster
     * return 1 << BitUtils::getMSB($mask) === $mask;
     */
    public static function isSingleBit(int $mask): bool
    {
        return count(self::getSetBits($mask)) === 1;
    }

    /**
     * single bit to index (left > right)
     * @throws NotSingleBitException
     */
    public static function bitToIndex(int $mask): int
    {
        if (!self::isSingleBit($mask)) {
            throw new NotSingleBitException('Argument must be a single bit');
        }
        return (int)log($mask, 2);
    }

    /**
     * index to single bit
     * @example 0 => 0b1 (1), 1 => 0b10 (2), 2 => 0b100 (4), ...
     * @throws InvalidIndexException
     * @see benchmarks/IndexToBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IndexToBitBench.php --report=default
     */
    public static function indexToBit(int $index): int
    {
        if ($index < 0) {
            throw new InvalidIndexException('Index (zero based) must be greater than or equal to zero');
        }
        return 1 << $index;
    }

    public static function toString(int $mask): string
    {
        return decbin($mask);
    }

    /**
     * @return int[]
     * @see benchmarks/GetSetBitsIndexBench.php
     * ./vendor/bin/phpbench run benchmarks/GetSetBitsIndexBench.php --report=default
     */
    public static function getSetBitsIndexes(int $mask): array
    {
        $bitIndexes = [];
        foreach (self::getSetBits($mask) as $index => $bit) {
            $bitIndexes[$index] = intval(log($bit, 2));
        }
        return $bitIndexes;
    }

    /**
     * Bitwise-based check if given number is even
     * @see benchmarks/EvenOddBench.php
     * ./vendor/bin/phpbench run benchmarks/EvenOddBench.php --report=default
     */
    public static function isEvenNumber(int $number): bool
    {
        return ($number & 1) === 0;
    }

    /**
     * Bitwise-based check if given number is odd
     * @see benchmarks/EvenOddBench.php
     * ./vendor/bin/phpbench run benchmarks/EvenOddBench.php --report=default
     */
    public static function isOddNumber(int $number): bool
    {
        return ($number & 1) === 1;
    }
}
