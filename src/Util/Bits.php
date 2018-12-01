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
     * @param int $mask
     * @return bool
     */
    public static function isSingleBit(int $mask): bool
    {
        return count(self::getSetBits($mask)) === 1;
    }

    /**
     * @param int $mask
     * @return bool
     */
    public static function isSingleBit2(int $mask): bool
    {
        return pow(2, self::getMSB($mask)) === $mask;
    }

    /**
     * @param int $mask
     * @return bool
     */
    public static function isSingleBit3(int $mask): bool
    {
        return 1 << self::getMSB($mask) === $mask;
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
     * @param int $index
     * @return int
     * @throws \Exception
     */
    public static function indexToBit(int $index): int
    {
        if ($index < 0) {
            throw new \Exception('index must be > 0');
        }
        return pow(2, $index);
    }

    /**
     * @param int $index
     * @return int
     */
    public static function indexToBit2(int $index): int
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

    /**
     * @param int $mask
     * @return array
     */
    public static function getSetBitsIndexes2(int $mask): array
    {
        $bitIndexes = [];
        foreach (self::getSetBits($mask) as $index => $bit) {
            $bitIndexes[$index] = (int)log($bit, 2);
        }
        return $bitIndexes;
    }
}
