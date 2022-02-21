<?php

declare(strict_types=1);

namespace BitMask\Tests\Util;

use BitMask\Exception\InvalidIndexException;
use BitMask\Exception\NotSingleBitException;
use BitMask\Util\Bits;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class BitsTest extends TestCase
{

    public function testGetMSB()
    {
        assertEquals(0, Bits::getMSB(-1));
        assertEquals(0, Bits::getMSB(0));
        assertEquals(1, Bits::getMSB(1));
        assertEquals(4, Bits::getMSB(8));
        assertEquals(4, Bits::getMSB(15));
        /** @todo check PHP_INT_MAX */
//        assertEquals(4, Bits::getMSB(PHP_INT_MAX));
    }

    public function testGetSetBits()
    {
        assertEquals([], Bits::getSetBits(0));
        assertEquals([1, 2, 4], Bits::getSetBits(7));
    }

    public function testIsSingleBit()
    {
        assertTrue(Bits::isSingleBit(8));
        assertFalse(Bits::isSingleBit(7));
    }

    public function testBitToIndex()
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

    public function testIndexToBit()
    {
        // valid index
        assertEquals(8, Bits::indexToBit(3));
        assertEquals(1, Bits::indexToBit(0)); // Bits.php:103 [M] LessThan
        // invalid index
        try {
            Bits::indexToBit(-1);
        } catch (InvalidIndexException $exception) {
            assertSame('Index (zero based) must be greater than or equal to zero', $exception->getMessage());
        }
    }

    public function testToString()
    {
        assertEquals('111', Bits::toString(7));
    }

    public function testGetSetBitsIndexes()
    {
        assertEquals([0, 1, 2], Bits::getSetBitsIndexes(7));
    }

    public function testIsEvenNumber()
    {
        assertTrue(Bits::isEvenNumber(2));
        assertFalse(Bits::isEvenNumber(1));
    }

    public function testIsOddNumber()
    {
        assertTrue(Bits::isOddNumber(3));
        assertFalse(Bits::isOddNumber(4));
    }
}
