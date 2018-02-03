<?php

namespace BitMask\Util;

final class Bits
{
    
    /**
     * get most significant bit position (left)
     *
     * @param int $integer
     * @return int
     */
    public static function getMSB(int $integer) : int
    {
        $scan = 1;
        $msb = 0;
        while ($integer >= $scan) {
            $scan <<= 1;
            if ($integer < $scan) {
                break;
            }
            $msb++;
        }
        return $msb;
    }

    /**
     * get used bits, capacity size
     *  10010 => 5, 111 => 3, 00010 => 2 (msb = 1)
     *
     * @param int $integer
     * @return int
     */
    public static function getBitCapacity(int $integer) : int
    {
        $msb = self::getMSB($integer);
        return $msb === 0 ? 0 : $msb + 1;
    }

    /**
     * get array of set bits
     *  10010 => [2, 16], 111 => [1, 2, 4]
     *
     * @param  int   $integer
     * @return array
     */
    public static function getSetBits(int $integer) : \Generator
    {
        $scan = 1;
        while ($integer >= $scan) {
            if ($integer & $scan) {
                yield $scan;
            }
            $scan <<= 1;
        }
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
        return count(iterator_to_array(self::getSetBits($integer))) === 1;
    }

    public static function isSingleBit2(int $integer) : bool
    {
        return pow(2, (self::getBitCapacity($integer) - 1)) === $integer;
    }

    public static function isSingleBit3(int $integer) : bool
    {
        return 1 << (self::getBitCapacity($integer) - 1) === $integer;
    }
}
