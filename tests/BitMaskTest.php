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
        new BitMask(-2);
    }

    public function testBitMaskConstructOutOfRange(): void
    {
        $bitmask = new BitMask(15, 3);
        assertInstanceOf(BitMask::class, $bitmask);
        assertSame(15, $bitmask->get());
        $this->expectException(OutOfRangeException::class);
        new BitMask(16, 3);
    }

    public function testSet(): void
    {
        $bitmask = new BitMask(0, 1);
        $bitmask->set(self::READ);
        assertEquals(self::READ, $bitmask->get());
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string) self::EXECUTE);
        $bitmask->set(self::EXECUTE);
    }

    public function testIsSet(): void
    {
        $bitmask = new BitMask(self::WRITE | self::EXECUTE);
        assertTrue($bitmask->has(self::WRITE, self::EXECUTE));
        assertFalse($bitmask->has(self::READ));
        assertTrue($bitmask->has(self::WRITE));
        assertTrue($bitmask->has(self::EXECUTE));
        assertFalse($bitmask->has(self::READ, self::WRITE));
        assertFalse($bitmask->has(self::READ, self::EXECUTE));
        assertFalse($bitmask->has(self::READ, self::WRITE, self::EXECUTE));
    }

    public function testHasNotSingleBit(): void
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string) (self::READ | self::WRITE));
        $bitmask->has(self::READ | self::WRITE);
    }

    public function testIsSetBitOutOfRange(): void
    {
        $bitmask = new BitMask(0, 1);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string) self::EXECUTE);
        $bitmask->has(self::EXECUTE);
    }

    public function testSetBit(): void
    {
        $bitmask = new BitMask();
        $bitmask->set(self::READ);
        assertTrue($bitmask->has(self::READ));
        assertSame(self::READ, $bitmask->get());
        $bitmask->set(self::WRITE);
        assertSame(self::READ | self::WRITE, $bitmask->get());
    }

    public function testSetNotSingleBit(): void
    {
        $bitmask = new BitMask();
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string) (self::READ | self::WRITE));
        $bitmask->set(self::READ | self::WRITE);
    }

    public function testSetBitOutOfRange(): void
    {
        $bitmask = new BitMask(0, 1);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string) self::EXECUTE);
        $bitmask->set(self::EXECUTE);
    }

    public function testRemoveBit(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE);
        $bitmask->remove(self::READ);
        assertFalse($bitmask->has(self::READ));
        assertTrue($bitmask->has(self::WRITE));
        $bitmask->remove(self::WRITE);
        assertFalse($bitmask->has(self::READ));
        assertFalse($bitmask->has(self::WRITE));
    }

    public function testUnsetBitNotSingleBit(): void
    {
        $bitmask = new BitMask(self::EXECUTE);
        $this->expectException(NotSingleBitException::class);
        $this->expectExceptionMessage((string) (self::READ | self::WRITE));
        $bitmask->remove(self::READ | self::WRITE);
    }

    public function testUnsetBitOutOfRange(): void
    {
        $bitmask = new BitMask(self::WRITE, 1);
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string) self::EXECUTE);
        $bitmask->remove(self::EXECUTE);
    }

    public function testToString(): void
    {
        $bitmask = new BitMask(7);
        assertSame('7', (string) $bitmask);
        $bitmask->set(8);
        assertSame('15', (string) $bitmask);
    }

    public function testRemoveTwice(): void
    {
        $bitmask = new BitMask(self::READ | self::WRITE | self::EXECUTE);
        $bitmask->remove(self::READ);
        assertFalse($bitmask->has(self::READ));
        $bitmask->remove(self::READ);
        assertFalse($bitmask->has(self::READ));
        assertSame(self::WRITE | self::EXECUTE, $bitmask->get());
    }
}
