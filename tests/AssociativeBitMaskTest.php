<?php

namespace BitMask\Tests;

use BitMask\AssociativeBitMask;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssociativeBitMaskTest extends TestCase
{
    /**
     * @covers \BitMask\AssociativeBitMask::__construct
     */
    public function testAssociativeBitMask()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $this->assertInstanceOf(AssociativeBitMask::class, $bitmask);
    }

    /**
     * @covers \BitMask\AssociativeBitMask::get
     */
    public function testGet()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\AssociativeBitMask::set
     */
    public function testSet()
    {
        $bitmask = new AssociativeBitMask(['1', '2', '4']);
        $bitmask->set(7);
        $this->assertEquals(7, $bitmask->get());
        $bitmask->set();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\AssociativeBitMask::unset
     */
    public function testUnset()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     * @covers \BitMask\AssociativeBitMask::isSet
     */
    public function testIsSet()
    {
        $bitmask = new AssociativeBitMask(['1', '2', '4'], 7);
        $this->assertTrue($bitmask->isSet(7));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(7));
    }

    /**
     * @todo not working properly
     * @covers \BitMask\AssociativeBitMask::isSetBit
     */
    public function testIsSetBit()
    {
        $bitmask = new AssociativeBitMask(['1', '2', '3', '4']);
        $this->assertFalse($bitmask->isSetBit(8));
//        $this->assertTrue($bitmask->isSetBit(4));
    }

    /**
     * @covers \BitMask\AssociativeBitMask::setBit
     */
    public function testSetBit()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

    /**
     * @covers \BitMask\AssociativeBitMask::unsetBit
     */
    public function testUnsetBit()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }
}
