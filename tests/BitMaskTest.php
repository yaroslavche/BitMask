<?php

namespace BitMask\Tests;

use BitMask\BitMask;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BitMaskTest extends TestCase
{
    /**
     * @covers \BitMask\BitMask::__construct
     */
    public function testBitMask()
    {
        $bitmask = new BitMask();
        $this->assertInstanceOf(BitMask::class, $bitmask);
    }

    /**
     * @covers \BitMask\BitMask::get
     */
    public function testGet()
    {
        $bitmask = new BitMask();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @covers \BitMask\BitMask::set
     */
    public function testSet()
    {
        $bitmask = new BitMask();
        $bitmask->set(PHP_INT_MAX);
        $this->assertEquals(PHP_INT_MAX, $bitmask->get());
        $bitmask->set();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @covers \BitMask\BitMask::unset
     */
    public function testUnset()
    {
        $bitmask = new BitMask(PHP_INT_MAX);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @covers \BitMask\BitMask::isSet
     */
    public function testIsSet()
    {
        $bitmask = new BitMask(PHP_INT_MAX);
        $this->assertTrue($bitmask->isSet(PHP_INT_MAX));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(PHP_INT_MAX));
    }

    /**
     * @covers \BitMask\BitMask::isSetBit
     */
    public function testIsSetBit()
    {
        $bitmask = new BitMask(7);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->assertTrue($bitmask->isSetBit(4));
    }

    /**
     * @covers \BitMask\BitMask::setBit
     */
    public function testSetBit()
    {
        $bitmask = new BitMask();
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

    /**
     * @covers \BitMask\BitMask::unsetBit
     */
    public function testUnsetBit()
    {
        $bitmask = new BitMask();
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }
}
