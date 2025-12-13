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
        assertEquals(0, Bits::getMostSignificantBit(PHP_INT_MIN));
        assertEquals(0, Bits::getMostSignificantBit(-1));
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
        assertEquals([1], Bits::getSetBits(1));
        assertEquals([2], Bits::getSetBits(2));
    }

    public function testIsSingleBit(): void
    {
        assertFalse(Bits::isSingleBit(0));
        assertTrue(Bits::isSingleBit(8));
        assertFalse(Bits::isSingleBit(7));
    }

    public function testBitToIndex(): void
    {
        assertEquals(3, Bits::bitToIndex(8));
        try {
            $result = Bits::bitToIndex(7);
            assertNull($result);
        } catch (NotSingleBitException $exception) {
            assertSame('Argument must be a single bit', $exception->getMessage());
        }
    }

    public function testIndexToBit(): void
    {
        assertEquals(8, Bits::indexToBit(3));
        assertEquals(1, Bits::indexToBit(0));
        $validMaxIndex = PHP_INT_SIZE * 8 - 1;
        $expectedMaxBit = 1 << $validMaxIndex;
        assertEquals($expectedMaxBit, Bits::indexToBit($validMaxIndex));
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
        assertEquals([], Bits::getSetBitsIndexes(0));
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

    public function testGetMostSignificantBitWithNegativeAndBoundary(): void
    {
        assertEquals(0, Bits::getMostSignificantBit(0));
        assertEquals(0, Bits::getMostSignificantBit(-1));
        assertEquals(0, Bits::getMostSignificantBit(-2));
    }

    public function testIsSingleBitWithNegative(): void
    {
        assertFalse(Bits::isSingleBit(-1));
        assertFalse(Bits::isSingleBit(-(1 << 3)));
    }

    public function testIndexToBitOutOfRange(): void
    {
        try {
            Bits::indexToBit(-1);
            $this->fail('Expected OutOfRangeException for index -1');
        } catch (OutOfRangeException $exception) {
            assertSame("-1", $exception->getMessage());
        }
    }

    public function testGetSetBitsIndexesBoundary(): void
    {
        $maxValidIndex = PHP_INT_SIZE * 8 - 2;
        $expectedIndices = range(0, $maxValidIndex);

        assertEquals($expectedIndices, Bits::getSetBitsIndexes(PHP_INT_MAX));
        assertSame($maxValidIndex + 1, count(Bits::getSetBitsIndexes(PHP_INT_MAX)));

        $mask63 = 1 << (PHP_INT_SIZE * 8 - 1);
        assertEquals([], Bits::getSetBitsIndexes($mask63));
        assertSame(0, count(Bits::getSetBitsIndexes($mask63)));
        assertEquals([], Bits::getSetBits($mask63));
        assertSame(0, count(Bits::getSetBits($mask63)));

        $mask62 = 1 << (PHP_INT_SIZE * 8 - 2);
        assertEquals([PHP_INT_SIZE * 8 - 2], Bits::getSetBitsIndexes($mask62));
        assertSame(1, count(Bits::getSetBitsIndexes($mask62)));
        assertEquals([1 << PHP_INT_SIZE * 8 - 2], Bits::getSetBits($mask62));
        assertSame(1, count(Bits::getSetBits($mask62)));

        $mask0 = 0;
        assertEquals([], Bits::getSetBitsIndexes($mask0));
        assertSame(0, count(Bits::getSetBitsIndexes($mask0)));
        assertEquals([], Bits::getSetBits($mask0));
        assertSame(0, count(Bits::getSetBits($mask0)));
    }

    public function testGetSetBitsIndexesNegative(): void
    {
        $mask = -1;
        assertEquals([], Bits::getSetBitsIndexes($mask));
    }
}
