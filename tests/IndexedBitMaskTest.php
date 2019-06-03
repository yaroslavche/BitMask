<?php

namespace BitMask\Tests;

use BitMask\IndexedBitMask;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IndexedBitMaskTest extends TestCase
{
    /**
     * @covers \BitMask\IndexedBitMask::__construct
     */
    public function testIndexedBitMask()
    {
        $bitmask = new IndexedBitMask();
        $this->assertInstanceOf(IndexedBitMask::class, $bitmask);
    }

    /**
     * @covers \BitMask\IndexedBitMask::get
     */
    public function testGet()
    {
        $bitmask = new IndexedBitMask();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\IndexedBitMask::set
     */
    public function testSet()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->set(7);
        $this->assertEquals(7, $bitmask->get());
        $bitmask->set();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\IndexedBitMask::unset
     */
    public function testUnset()
    {
        $bitmask = new IndexedBitMask(7);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\IndexedBitMask::isSet
     */
    public function testIsSet()
    {
        $bitmask = new IndexedBitMask(7);
        $this->assertTrue($bitmask->isSet(7));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(7));
    }

    /**
     * @covers \BitMask\IndexedBitMask::isSetBit
     */
    public function testIsSetBit()
    {
        $bitmask = new IndexedBitMask(7);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->assertTrue($bitmask->isSetBit(4));
    }

    /**
     * @covers \BitMask\IndexedBitMask::setBit
     */
    public function testSetBit()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

    /**
     * @covers \BitMask\IndexedBitMask::unsetBit
     */
    public function testUnsetBit()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }
}
