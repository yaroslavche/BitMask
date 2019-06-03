<?php

namespace BitMask\Tests;

use BitMask\BitMask;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BitMaskTest extends TestCase
{
    public function testBitMask()
    {
        $bitmask = new BitMask();
        $this->assertInstanceOf(BitMask::class, $bitmask);
    }

    public function testGet()
    {
        $bitmask = new BitMask();
        $this->assertEquals(0, $bitmask->get());
    }

    public function testSet()
    {
        $bitmask = new BitMask();
        $bitmask->set(PHP_INT_MAX);
        $this->assertEquals(PHP_INT_MAX, $bitmask->get());
        $bitmask->set();
        $this->assertEquals(0, $bitmask->get());
    }

    public function testUnset()
    {
        $bitmask = new BitMask(PHP_INT_MAX);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    public function testIsSet()
    {
        $bitmask = new BitMask(PHP_INT_MAX);
        $this->assertTrue($bitmask->isSet(PHP_INT_MAX));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(PHP_INT_MAX));
    }

    public function testIsSetBit()
    {
        $bitmask = new BitMask(7);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->assertTrue($bitmask->isSetBit(4));
    }

    public function testSetBit()
    {
        $bitmask = new BitMask();
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

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

    public function testToString()
    {
        $bitmask = new BitMask(7);
        $this->assertSame('7', (string)$bitmask);
        $bitmask->set(9);
        $this->assertSame('9', (string)$bitmask);
    }

    public function testInvoke()
    {
        $bitmask = new BitMask(7);
        $this->assertTrue($bitmask(1));
        $this->assertFalse($bitmask(8));
        $bitmask->set(9);
        $this->assertTrue($bitmask(8));
        $this->assertFalse($bitmask(4));
    }

    public function testInit()
    {
        $bitmask = BitMask::init(7);
        $this->assertInstanceOf(BitMask::class, $bitmask);
    }
}
