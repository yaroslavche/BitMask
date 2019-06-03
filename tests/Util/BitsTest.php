<?php

namespace BitMask\Tests\Util;

use BitMask\Util\Bits;
use Exception;
use PHPUnit\Framework\TestCase;

class BitsTest extends TestCase
{
    public function testGetMSB()
    {
        $this->assertEquals(0, Bits::getMSB(0));
        $this->assertEquals(1, Bits::getMSB(1));
        $this->assertEquals(4, Bits::getMSB(8));
    }

    public function testGetSetBits()
    {
        $this->assertEquals([], Bits::getSetBits(0));
        $this->assertEquals([1, 2, 4], Bits::getSetBits(7));
    }

    public function testIsSingleBit()
    {
        $this->assertTrue(Bits::isSingleBit(8));
        $this->assertFalse(Bits::isSingleBit(7));
    }

    public function testBitToIndex()
    {
        $this->assertEquals(3, Bits::bitToIndex(8));
        try {
            Bits::bitToIndex(7);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }
    }

    public function testIndexToBit()
    {
        $this->assertEquals(8, Bits::indexToBit(3));
        try {
            Bits::indexToBit(-1);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }
    }

    public function testToString()
    {
        $this->assertEquals('111', Bits::toString(7));
    }

    public function testGetSetBitsIndexes()
    {
        $this->assertEquals([0, 1, 2], Bits::getSetBitsIndexes(7));
    }

    public function testIsEvenNumber()
    {
        $this->assertTrue(Bits::isEvenNumber(2));
        $this->assertFalse(Bits::isEvenNumber(1));
    }

    public function testIsOddNumber()
    {
        $this->assertTrue(Bits::isOddNumber(3));
        $this->assertFalse(Bits::isOddNumber(4));
    }
}
