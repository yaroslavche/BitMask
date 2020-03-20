<?php

namespace BitMask\Tests;

use BitMask\Exception\NotSingleBitException;
use BitMask\IndexedBitMask;
use Exception;
use InvalidArgumentException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

class IndexedBitMaskTest extends TestCase
{
    public function testIndexedBitMask()
    {
        $bitmask = new IndexedBitMask();
        $this->assertInstanceOf(IndexedBitMask::class, $bitmask);
    }

    public function testGet()
    {
        $bitmask = new IndexedBitMask();
        $this->assertEquals(0, $bitmask->get());
    }

    public function testGetByIndex()
    {
        $bitmask = new IndexedBitMask(5, 3);
        $this->assertTrue($bitmask->getByIndex(0));
        $this->assertFalse($bitmask->getByIndex(1));
        $this->assertTrue($bitmask->getByIndex(2));
        try {
            $bitmask->getByIndex(-1);
        } catch (OutOfRangeException $exception) {
            $this->assertSame('-1', $exception->getMessage());
        }
        try {
            $bitmask->getByIndex(3);
        } catch (OutOfRangeException $exception) {
            $this->assertSame('3', $exception->getMessage());
        }
    }

    public function testSet()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->set(7);
        $this->assertEquals(7, $bitmask->get());
        $bitmask->set(0);
        $this->assertEquals(0, $bitmask->get());
    }

    public function testUnset()
    {
        $bitmask = new IndexedBitMask(7);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    public function testIsSet()
    {
        $bitmask = new IndexedBitMask(7);
        $this->assertTrue($bitmask->isSet(7));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(7));
    }

    public function testIsSetBit()
    {
        $bitmask = new IndexedBitMask(7);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->assertTrue($bitmask->isSetBit(4));
    }

    public function testSetBit()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new NotSingleBitException('3'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

    public function testUnsetBit()
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new NotSingleBitException('3'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }
}
