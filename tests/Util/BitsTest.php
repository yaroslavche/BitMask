<?php

declare(strict_types=1);

namespace BitMask\Tests\Util;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use BitMask\Util\Bits;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class BitsTest extends TestCase
{

    public function testGetMostSignificantBit(): void
    {
        assertEquals(0, Bits::getMostSignificantBit(0));
        assertEquals(0, Bits::getMostSignificantBit(1));
        assertEquals(3, Bits::getMostSignificantBit(8));
        assertEquals(3, Bits::getMostSignificantBit(15));
        assertEquals(63, Bits::getMostSignificantBit(PHP_INT_MAX));
    }

    public function testGetSetBits(): void
    {
        assertEquals([], Bits::getSetBits(0));
        assertEquals([1, 2, 4], Bits::getSetBits(7));
        assertEquals([2, 16], Bits::getSetBits(0b10010));
        assertEquals([1], Bits::getSetBits(1)); // Util/Bits.php:31 [M] GreaterThanOrEqualTo
        assertEquals([2], Bits::getSetBits(2)); // Util/Bits.php:32 [M] BitwiseAnd
    }

    public function testIsSingleBit(): void
    {
        assertTrue(Bits::isSingleBit(8));
        assertFalse(Bits::isSingleBit(7));
    }

    public function testBitToIndex(): void
    {
        // single bit
        assertEquals(3, Bits::bitToIndex(8));
        // not single bit
        try {
            $result = Bits::bitToIndex(7);
            assertNull($result);
        } catch (NotSingleBitException $exception) {
            assertSame('Argument must be a single bit', $exception->getMessage());
        }
    }

    public function testIndexToBit(): void
    {
        // valid index
        assertEquals(8, Bits::indexToBit(3));
        assertEquals(1, Bits::indexToBit(0)); // Bits.php:103 [M] LessThan
        // invalid index
        try {
            Bits::indexToBit(-1);
        } catch (OutOfRangeException $exception) {
            assertSame('-1', $exception->getMessage());
        }
    }

    public function testToString(): void
    {
        assertEquals('111', Bits::toString(7));
    }

    public function testGetSetBitsIndexes(): void
    {
        assertEquals([0, 1, 2], Bits::getSetBitsIndexes(7));
    }

    public function testIsEvenNumber(): void
    {
        assertTrue(Bits::isEvenNumber(2));
        assertFalse(Bits::isEvenNumber(1));
    }

    public function testIsOddNumber(): void
    {
        assertTrue(Bits::isOddNumber(3));
        assertFalse(Bits::isOddNumber(4));
    }
}
