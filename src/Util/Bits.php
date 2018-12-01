<?php
declare(strict_types=1);

namespace BitMask\Util;

/**
 * Class Bits
 * @package BitMask\Util
 */
final class Bits
{

    /**
     * @todo read and use https://www.geeksforgeeks.org/find-significant-set-bit-number/
     * get most significant bit position (right -> left)
     *  10001 -> 5, 0010 -> 2, 00100010 -> 6
     *
     * @param int $mask
     * @return int
     */
    public static function getMSB(int $mask): int
    {
        $scan = 1;
        $msb = 0;
        while ($mask >= $scan) {
            $msb++;
            $scan <<= 1;
            if ($mask < $scan) {
                break;
            }
        }
        return $msb;
    }

    /**
     * get array of set bits
     *  10010 => [2, 16], 111 => [1, 2, 4]
     *
     * @param  int $mask
     * @return array
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
     *  1000 => true, 010100 => false, 0000100 => true
     *
     * @see benchmarks/IsSingleBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IsSingleBitBench.php --report=default
     * benchIsSingleBit1             I4 P0         [μ Mo]/r: 2.493 2.478 (μs)      [μSD μRSD]/r: 0.079μs 3.17%
     * benchIsSingleBit2             I4 P0         [μ Mo]/r: 2.917 2.857 (μs)      [μSD μRSD]/r: 0.081μs 2.77%
     * benchIsSingleBit3             I4 P0         [μ Mo]/r: 2.497 2.508 (μs)      [μSD μRSD]/r: 0.026μs 1.04%
     *
     * maybe getMSB must return shift offset and then isSingleBit3 might be faster
     * return 1 << BitUtils::getMSB($mask) === $mask;
     *
     * @param int $mask
     * @return bool
     */
    public static function isSingleBit(int $mask): bool
    {
        return count(self::getSetBits($mask)) === 1;
    }

    /**
     * single bit to index (left > right)
     *
     * @param int $mask single bit
     * @return int
     * @throws \Exception
     */
    public static function bitToIndex(int $mask): int
    {
        if (!self::isSingleBit($mask)) {
            throw new \Exception('Must be single bit');
        }
        return (int)log($mask, 2);
    }

    /**
     * index to single bit
     *  0 => 0b1 (1), 1 => 0b10 (2), 2 => 0b100 (4), ...
     *
     * @see benchmarks/IndexToBitBench.php
     * ./vendor/bin/phpbench run benchmarks/IndexToBitBench.php --report=default
     * benchIndexToBit1              I4 P0         [μ Mo]/r: 2.035 1.995 (μs)      [μSD μRSD]/r: 0.053μs 2.61%
     * benchIndexToBit2              I4 P0         [μ Mo]/r: 1.501 1.486 (μs)      [μSD μRSD]/r: 0.037μs 2.45%
     *
     * @param int $index
     * @return int
     * @throws \Exception
     */
    public static function indexToBit(int $index): int
    {
        // if($index < 0) throw new \Exception('index must be > 0'); // no need - thrown ariphmetic exception
        return 1 << $index;
    }

    /**
     * @param int $mask
     * @return string
     */
    public static function toString(int $mask): string
    {
        return decbin($mask);
    }

    /**
     *  @see benchmarks/GetSetBitsIndexBench.php
     * ./vendor/bin/phpbench run benchmarks/GetSetBitsIndexBench.php --report=default
     * benchGetSetBitsIndex1         I4 P0         [μ Mo]/r: 1.668 1.686 (μs)      [μSD μRSD]/r: 0.046μs 2.76%
     * benchGetSetBitsIndex2         I4 P0         [μ Mo]/r: 2.580 2.610 (μs)      [μSD μRSD]/r: 0.041μs 1.61%
     *
     * benchGetSetBitsIndex2 don't throw exception
     *
     * @param int $mask
     * @return array
     * @throws \Exception
     */
    public static function getSetBitsIndexes(int $mask): array
    {
        $bitIndexes = [];
        $scan = 1;
        while ($mask >= $scan) {
            if ($mask & $scan) {
                $bitIndexes[] = self::bitToIndex($scan);
            }
            $scan <<= 1;
        }
        return $bitIndexes;
    }
}
