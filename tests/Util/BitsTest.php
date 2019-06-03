<?php

namespace BitMask\Tests\Util;

use BitMask\Util\Bits;
use PHPUnit\Framework\TestCase;

class BitsTest extends TestCase
{
    /**
     * @covers \BitMask\Util\Bits::getMSB
     */
    public function testGetMSB()
    {
        $this->assertEquals(0, Bits::getMSB(0));
        $this->assertEquals(1, Bits::getMSB(1));
        $this->assertEquals(4, Bits::getMSB(8));
    }

    /**
     * @covers \BitMask\Util\Bits::getSetBits
     */
    public function testGetSetBits()
    {
        $this->assertEquals([], Bits::getSetBits(0));
        $this->assertEquals([1, 2, 4], Bits::getSetBits(7));
    }

    /**
     * @covers \BitMask\Util\Bits::isSingleBit
     */
    public function testIsSingleBit()
    {
        $this->assertTrue(Bits::isSingleBit(8));
        $this->assertFalse(Bits::isSingleBit(7));
    }

    /**
     * @covers \BitMask\Util\Bits::bitToIndex
     */
    public function testBitToIndex()
    {
        $this->assertEquals(3, Bits::bitToIndex(8));
    }

    /**
     * @covers \BitMask\Util\Bits::indexToBit
     */
    public function testIndexToBit()
    {
        $this->assertEquals(8, Bits::indexToBit(3));
    }

    /**
     * @covers \BitMask\Util\Bits::toString
     */
    public function testToString()
    {
        $this->assertEquals('111', Bits::toString(7));
    }

    /**
     * @covers \BitMask\Util\Bits::getSetBitsIndexes
     */
    public function testGetSetBitsIndexes()
    {
        $this->assertEquals([0, 1, 2], Bits::getSetBitsIndexes(7));
    }

    /**
     * @covers \BitMask\Util\Bits::isEvenNumber
     */
    public function testIsEvenNumber()
    {
        $this->assertTrue(Bits::isEvenNumber(2));
        $this->assertFalse(Bits::isEvenNumber(1));
    }

    /**
     * @covers \BitMask\Util\Bits::isOddNumber
     */
    public function testIsOddNumber()
    {
        $this->assertTrue(Bits::isOddNumber(3));
        $this->assertFalse(Bits::isOddNumber(4));
    }
}
