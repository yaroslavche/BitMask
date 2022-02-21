<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\BitMask;
use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class BitMaskTest extends TestCase
{
    private const READ = 1 << 0;
    private const WRITE = 1 << 1;
    private const EXECUTE = 1 << 2;

    public function testBitMask()
    {
        $bitmask = new BitMask();
        assertInstanceOf(BitMask::class, $bitmask);
        assertNull($bitmask->get());
    }

    public function testSet()
    {
        $bitmask = new BitMask(null, 2);
        $bitmask->set(static::READ);
        assertEquals(static::READ, $bitmask->get());
        $bitmask->set(0); // check mutation LessThanOrEqualTo BitMask.php:68
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)static::EXECUTE);
        $bitmask->set(static::EXECUTE);
    }

    public function testUnset()
    {
        $bitmask = new BitMask(static::READ | static::EXECUTE);
        $bitmask->unset();
        assertNull($bitmask->get());
    }

    public function testIsSet()
    {
        $bitmask = new BitMask(static::WRITE | static::EXECUTE);
        assertTrue($bitmask->isSet(static::WRITE | static::EXECUTE));
        assertFalse($bitmask->isSet(static::READ));
        assertTrue($bitmask->isSet(static::WRITE));
        assertTrue($bitmask->isSet(static::EXECUTE));
        assertFalse($bitmask->isSet(static::READ | static::WRITE));
        assertFalse($bitmask->isSet(static::READ | static::EXECUTE));
        assertFalse($bitmask->isSet(static::READ | static::WRITE | static::EXECUTE));
    }

    public function testIsSetBit()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE, 3);
        assertTrue($bitmask->isSetBit(static::READ));
        assertTrue($bitmask->isSetBit(static::WRITE));
        assertTrue($bitmask->isSetBit(static::EXECUTE));
        $bitmask->unset();
        assertFalse($bitmask->isSetBit(static::READ));
        assertFalse($bitmask->isSetBit(static::WRITE));
        assertFalse($bitmask->isSetBit(static::EXECUTE));
    }

    public function testIsSetBitNotSingleBit()
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(static::READ | static::WRITE));
        $bitmask->isSetBit(static::READ | static::WRITE);
    }

    public function testIsSetBitOutOfRange()
    {
        $bitmask = new BitMask(null, 2);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)static::EXECUTE);
        $bitmask->isSetBit(static::EXECUTE);
    }

    public function testSetBit()
    {
        $bitmask = new BitMask();
        $bitmask->setBit(static::READ);
        assertTrue($bitmask->isSetBit(static::READ));
        assertSame(static::READ, $bitmask->get());
        $bitmask->setBit(static::WRITE);
        assertSame(static::READ | static::WRITE, $bitmask->get());
    }

    public function testSetBitNotSingleBit()
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(static::READ | static::WRITE));
        $bitmask->setBit(static::READ | static::WRITE);
    }

    public function testSetBitOutOfRange()
    {
        $bitmask = new BitMask(null, 2);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)static::EXECUTE);
        $bitmask->setBit(static::EXECUTE);
    }

    public function testUnsetBit()
    {
        $bitmask = new BitMask(static::READ | static::WRITE);
        $bitmask->unsetBit(static::READ);
        assertFalse($bitmask->isSetBit(static::READ));
        assertTrue($bitmask->isSetBit(static::WRITE));
        $bitmask->unsetBit(static::WRITE);
        assertFalse($bitmask->isSetBit(static::READ));
        assertFalse($bitmask->isSetBit(static::WRITE));
    }

    public function testUnsetBitNotSingleBit()
    {
        $bitmask = new BitMask(static::EXECUTE);
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(static::READ | static::WRITE));
        $bitmask->unsetBit(static::READ | static::WRITE);
    }

    public function testUnsetBitOutOfRange()
    {
        $bitmask = new BitMask(static::WRITE, 2);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)static::EXECUTE);
        $bitmask->unsetBit(static::EXECUTE);
    }

    public function testToString()
    {
        $bitmask = new BitMask(7);
        assertSame('7', (string)$bitmask);
        $bitmask->set(9);
        assertSame('9', (string)$bitmask);
    }

    public function testInvoke()
    {
        $bitmask = new BitMask(7);
        assertTrue($bitmask(1));
        assertFalse($bitmask(8));
        $bitmask->set(9);
        assertTrue($bitmask(8));
        assertFalse($bitmask(4));
    }

    public function testInit()
    {
        $bitmask = BitMask::init(7);
        assertInstanceOf(BitMask::class, $bitmask);
    }

    public function testSetBitByShiftOffset()
    {
        $bitmask = new BitMask(null, 3);
        $bitmask->setBitByShiftOffset(0);
        assertTrue($bitmask->isSetBit(static::READ));
        $bitmask->setBitByShiftOffset(1);
        assertTrue($bitmask->isSetBit(static::WRITE));
        $bitmask->setBitByShiftOffset(2);
        assertTrue($bitmask->isSetBit(static::EXECUTE));
        assertSame(static::READ | static::WRITE | static::EXECUTE, $bitmask->get());
    }

    public function testUnsetBitByShiftOffset()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE);
        $bitmask->unsetBitByShiftOffset(0);
        assertFalse($bitmask->isSetBit(static::READ));
        $bitmask->unsetBitByShiftOffset(1);
        assertFalse($bitmask->isSetBit(static::WRITE));
        $bitmask->unsetBitByShiftOffset(2);
        assertFalse($bitmask->isSetBit(static::EXECUTE));
        assertSame(0, $bitmask->get());
    }

    public function testIsSetBitByShiftOffset()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE);
        assertTrue($bitmask->isSetBitByShiftOffset(0));
        assertTrue($bitmask->isSetBitByShiftOffset(1));
        assertTrue($bitmask->isSetBitByShiftOffset(2));
    }

    public function testShiftOffsetOutOfRange()
    {
        $bitmask = new BitMask(static::WRITE, 2);
        try {
            $bitmask->setBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            assertSame('2', $exception->getMessage());
        }
        try {
            $bitmask->unsetBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            assertSame('2', $exception->getMessage());
        }
        try {
            $bitmask->isSetBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            assertSame('2', $exception->getMessage());
        }
    }
}
