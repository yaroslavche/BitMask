<?php
declare(strict_types = 1);

namespace BitMask\Util;

final class Bits
{

    /**
     * get most significant bit position (right -> left)
     *  10001 -> 5, 0010 -> 2, 00100010 -> 6
     *
     * @param int $integer
     * @return int
     */
    public static function getMSB(int $integer) : int
    {
        $scan = 1;
        $msb = 0;
        while ($integer >= $scan) {
            $msb++;
            $scan <<= 1;
            if ($integer < $scan) {
                break;
            }
        }
        return $msb;
    }

    /**
     * get array of set bits
     *  10010 => [2, 16], 111 => [1, 2, 4]
     *
     * @param  int   $integer
     * @return array
     */
    public static function getSetBits(int $integer) : array
    {
        $bits = [];
        $scan = 1;
        while ($integer >= $scan) {
            if ($integer & $scan) {
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
     * @param int $integer
     * @return bool
     */
    public static function isSingleBit(int $integer) : bool
    {
        return count(self::getSetBits($integer)) === 1;
    }

    public static function isSingleBit2(int $integer) : bool
    {
        return pow(2, self::getMSB($integer)) === $integer;
    }

    public static function isSingleBit3(int $integer) : bool
    {
        return 1 << self::getMSB($integer) === $integer;
    }

    /**
     * single bit to index (left > right)
     *
     * @param int $integer single bit
     * @return int
     */
    public static function bitToIndex(int $integer) : int
    {
        if (!self::isSingleBit($integer)) {
            throw new \Exception('Must be single bit');
        }
        return (int)log($integer, 2);
    }

    /**
     * index to single bit
     *  0 => 0b1 (1), 1 => 0b10 (2), 2 => 0b100 (4), ...
     * @param int $index
     * @return int
     */
    public static function indexToBit(int $index) : int
    {
        if ($index < 0) {
            throw new \Exception('index must be > 0');
        }
        return pow(2, $index);
    }

    public static function indexToBit2(int $index) : int
    {
        // if($index < 0) throw new \Exception('index must be > 0'); // no need - thrown ariphmetic exception
        return 1 << $index;
    }
}
