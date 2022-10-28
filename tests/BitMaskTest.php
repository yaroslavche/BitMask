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
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class BitMaskTest extends TestCase
{
    private const READ = 1 << 0;
    private const WRITE = 1 << 1;
    private const EXECUTE = 1 << 2;

    public function testBitMask(): void
    {
        $bitmask = new BitMask();
        assertInstanceOf(BitMask::class, $bitmask);
        assertSame(0, $bitmask->get());
        $this->expectException(OutOfRangeException::class);
        new BitMask(-2); // BitMask.php:19 [M] MethodCallRemoval
    }

    public function testSet(): void
    {
        $bitmask = new BitMask(0, 1);
        $bitmask->set(self::READ);
        assertEquals(self::READ, $bitmask->get());
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)self::EXECUTE);
        $bitmask->set(self::EXECUTE);
    }

    public function testUnset(): void
    {
        $bitmask = new BitMask(self::READ | self::EXECUTE);
        $bitmask->unset();
        assertSame(0, $bitmask->get());
    }

    public function testIsSet(): void
    {
        $bitmask = new BitMask(self::WRITE | self::EXECUTE);
        assertTrue($bitmask->isSet(self::WRITE | self::EXECUTE));
        assertFalse($bitmask->isSet(self::READ));
        assertTrue($bitmask->isSet(self::WRITE));
        assertTrue($bitmask->isSet(self::EXECUTE));
        assertFalse($bitmask->isSet(self::READ | self::WRITE));
        assertFalse($bitmask->isSet(self::READ | self::EXECUTE));
        assertFalse($bitmask->isSet(self::READ | self::WRITE | self::EXECUTE));
    }

    public function testIsSetBit(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE | self::EXECUTE, 3);
        assertTrue($bitmask->isSetBits(self::READ));
        assertTrue($bitmask->isSetBits(self::WRITE));
        assertTrue($bitmask->isSetBits(self::EXECUTE));
        $bitmask->unset();
        assertFalse($bitmask->isSetBits(self::READ));
        assertFalse($bitmask->isSetBits(self::WRITE));
        assertFalse($bitmask->isSetBits(self::EXECUTE));
    }

    public function testIsSetBitNotSingleBit(): void
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(self::READ | self::WRITE));
        $bitmask->isSetBits(self::READ | self::WRITE);
    }

    public function testIsSetBitOutOfRange(): void
    {
        $bitmask = new BitMask(0, 1);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)self::EXECUTE);
        $bitmask->isSetBits(self::EXECUTE);
    }

    public function testSetBit(): void
    {
        $bitmask = new BitMask();
        $bitmask->setBits(self::READ);
        assertTrue($bitmask->isSetBits(self::READ));
        assertSame(self::READ, $bitmask->get());
        $bitmask->setBits(self::WRITE);
        assertSame(self::READ | self::WRITE, $bitmask->get());
    }

    public function testSetBitNotSingleBit(): void
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(self::READ | self::WRITE));
        $bitmask->setBits(self::READ | self::WRITE);
    }

    public function testSetBitOutOfRange(): void
    {
        $bitmask = new BitMask(0, 1);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)self::EXECUTE);
        $bitmask->setBits(self::EXECUTE);
    }

    public function testUnsetBit(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE);
        $bitmask->unsetBits(self::READ);
        assertFalse($bitmask->isSetBits(self::READ));
        assertTrue($bitmask->isSetBits(self::WRITE));
        $bitmask->unsetBits(self::WRITE);
        assertFalse($bitmask->isSetBits(self::READ));
        assertFalse($bitmask->isSetBits(self::WRITE));
    }

    public function testUnsetBitNotSingleBit(): void
    {
        $bitmask = new BitMask(self::EXECUTE);
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string)(self::READ | self::WRITE));
        $bitmask->unsetBits(self::READ | self::WRITE);
    }

    public function testUnsetBitOutOfRange(): void
    {
        $bitmask = new BitMask(self::WRITE, 2);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)self::EXECUTE);
        $bitmask->unsetBits(self::EXECUTE);
    }

    public function testToString(): void
    {
        $bitmask = new BitMask(7);
        assertSame('7', (string)$bitmask);
        $bitmask->set(9);
        assertSame('9', (string)$bitmask);
    }

    public function testSetBitByShiftOffset(): void
    {
        $bitmask = new BitMask(0, 3);
        $bitmask->setBitByShiftOffset(0);
        assertTrue($bitmask->isSetBits(self::READ));
        $bitmask->setBitByShiftOffset(1);
        assertTrue($bitmask->isSetBits(self::WRITE));
        $bitmask->setBitByShiftOffset(2);
        assertTrue($bitmask->isSetBits(self::EXECUTE));
        assertSame(self::READ | self::WRITE | self::EXECUTE, $bitmask->get());
    }

    public function testUnsetBitByShiftOffset(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE | self::EXECUTE);
        $bitmask->unsetBitByShiftOffset(0);
        assertFalse($bitmask->isSetBits(self::READ));
        $bitmask->unsetBitByShiftOffset(1);
        assertFalse($bitmask->isSetBits(self::WRITE));
        $bitmask->unsetBitByShiftOffset(2);
        assertFalse($bitmask->isSetBits(self::EXECUTE));
        assertSame(0, $bitmask->get());
    }

    public function testIsSetBitByShiftOffset(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE | self::EXECUTE);
        assertTrue($bitmask->isSetBitByShiftOffset(0));
        assertTrue($bitmask->isSetBitByShiftOffset(1));
        assertTrue($bitmask->isSetBitByShiftOffset(2));
    }

    public function testShiftOffsetOutOfRange(): void
    {
        assertSame(true, true);
        $bitmask = new BitMask(self::READ, 2);
        try {
            $bitmask->setBitByShiftOffset(3);
        } catch (OutOfRangeException $exception) {
            assertSame('8', $exception->getMessage());
        }
        try {
            $bitmask->unsetBitByShiftOffset(3);
        } catch (OutOfRangeException $exception) {
            assertSame('8', $exception->getMessage());
        }
        try {
            $bitmask->isSetBitByShiftOffset(3);
        } catch (OutOfRangeException $exception) {
            assertSame('8', $exception->getMessage());
        }
    }
}
