<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;
use BitMask\IndexedBitMask;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class IndexedBitMaskTest extends TestCase
{
    public function testIndexedBitMask(): void
    {
        $bitmask = new IndexedBitMask();
        assertInstanceOf(IndexedBitMask::class, $bitmask);
    }

    public function testGet(): void
    {
        $bitmask = new IndexedBitMask();
        assertEquals(0, $bitmask->get());
    }

    public function testGetByIndex(): void
    {
        $bitmask = new IndexedBitMask(5, 3);
        assertTrue($bitmask->getByIndex(0));
        assertFalse($bitmask->getByIndex(1));
        assertTrue($bitmask->getByIndex(2));
        try {
            $bitmask->getByIndex(-1);
        } catch (OutOfRangeException $exception) {
            assertSame('-1', $exception->getMessage());
        }
        try {
            $bitmask->getByIndex(3);
        } catch (OutOfRangeException $exception) {
            assertSame('3', $exception->getMessage());
        }
    }

    public function testSet(): void
    {
        $bitmask = new IndexedBitMask();
        $bitmask->set(7);
        assertEquals(7, $bitmask->get());
        $bitmask->set(0);
        assertEquals(0, $bitmask->get());
    }

    public function testUnset(): void
    {
        $bitmask = new IndexedBitMask(7);
        $bitmask->unset();
        assertEquals(0, $bitmask->get());
    }

    public function testIsSet(): void
    {
        $bitmask = new IndexedBitMask(7);
        assertTrue($bitmask->isSet(7));
        $bitmask->set(0);
        assertFalse($bitmask->isSet(7));
    }

    public function testIsSetBit(): void
    {
        $bitmask = new IndexedBitMask(7);
        assertFalse($bitmask->isSetBit(8));
        assertTrue($bitmask->isSetBit(4));
    }

    public function testSetBit(): void
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new NotSingleBitException('3'));
        $bitmask->setBit(3);
        assertEquals(8, $bitmask->get());
    }

    public function testUnsetBit(): void
    {
        $bitmask = new IndexedBitMask();
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new NotSingleBitException('3'));
        $bitmask->unsetBit(3);
        assertEquals(0, $bitmask->get());
    }
}
