<?php
declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\BitMask;
use BitMask\Exception\NotSingleBitException;
use InvalidArgumentException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

/**
 * Class BitMaskTest
 * @package BitMask\Tests
 */
class BitMaskTest extends TestCase
{
    private const READ = 1 << 0;
    private const WRITE = 1 << 1;
    private const EXECUTE = 1 << 2;

    public function testBitMask()
    {
        $bitmask = new BitMask();
        $this->assertInstanceOf(BitMask::class, $bitmask);
        $this->assertNull($bitmask->get());
    }

    public function testSet()
    {
        $bitmask = new BitMask(null, 2);
        $bitmask->set(static::READ);
        $this->assertEquals(static::READ, $bitmask->get());
        $bitmask->set(0); // check mutation LessThanOrEqualTo BitMask.php:68
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage((string)static::EXECUTE);
        $bitmask->set(static::EXECUTE);
    }

    public function testUnset()
    {
        $bitmask = new BitMask(static::READ | static::EXECUTE);
        $bitmask->unset();
        $this->assertNull($bitmask->get());
    }

    public function testIsSet()
    {
        $bitmask = new BitMask(static::WRITE | static::EXECUTE);
        $this->assertTrue($bitmask->isSet(static::WRITE | static::EXECUTE));
        $this->assertFalse($bitmask->isSet(static::READ));
        $this->assertTrue($bitmask->isSet(static::WRITE));
        $this->assertTrue($bitmask->isSet(static::EXECUTE));
        $this->assertFalse($bitmask->isSet(static::READ | static::WRITE));
        $this->assertFalse($bitmask->isSet(static::READ | static::EXECUTE));
        $this->assertFalse($bitmask->isSet(static::READ | static::WRITE | static::EXECUTE));
    }

    public function testIsSetBit()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE, 3);
        $this->assertTrue($bitmask->isSetBit(static::READ));
        $this->assertTrue($bitmask->isSetBit(static::WRITE));
        $this->assertTrue($bitmask->isSetBit(static::EXECUTE));
        $bitmask->unset();
        $this->assertFalse($bitmask->isSetBit(static::READ));
        $this->assertFalse($bitmask->isSetBit(static::WRITE));
        $this->assertFalse($bitmask->isSetBit(static::EXECUTE));
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
        $this->assertTrue($bitmask->isSetBit(static::READ));
        $this->assertSame(static::READ, $bitmask->get());
        $bitmask->setBit(static::WRITE);
        $this->assertSame(static::READ | static::WRITE, $bitmask->get());
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
        $this->assertFalse($bitmask->isSetBit(static::READ));
        $this->assertTrue($bitmask->isSetBit(static::WRITE));
        $bitmask->unsetBit(static::WRITE);
        $this->assertFalse($bitmask->isSetBit(static::READ));
        $this->assertFalse($bitmask->isSetBit(static::WRITE));
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

    public function testSetBitByShiftOffset()
    {
        $bitmask = new BitMask(null, 3);
        $bitmask->setBitByShiftOffset(0);
        $this->assertTrue($bitmask->isSetBit(static::READ));
        $bitmask->setBitByShiftOffset(1);
        $this->assertTrue($bitmask->isSetBit(static::WRITE));
        $bitmask->setBitByShiftOffset(2);
        $this->assertTrue($bitmask->isSetBit(static::EXECUTE));
        $this->assertSame(static::READ | static::WRITE | static::EXECUTE, $bitmask->get());
    }

    public function testUnsetBitByShiftOffset()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE);
        $bitmask->unsetBitByShiftOffset(0);
        $this->assertFalse($bitmask->isSetBit(static::READ));
        $bitmask->unsetBitByShiftOffset(1);
        $this->assertFalse($bitmask->isSetBit(static::WRITE));
        $bitmask->unsetBitByShiftOffset(2);
        $this->assertFalse($bitmask->isSetBit(static::EXECUTE));
        $this->assertSame(0, $bitmask->get());
    }

    public function testIsSetBitByShiftOffset()
    {
        $bitmask = new BitMask(static::READ | static::WRITE | static::EXECUTE);
        $this->assertTrue($bitmask->isSetBitByShiftOffset(0));
        $this->assertTrue($bitmask->isSetBitByShiftOffset(1));
        $this->assertTrue($bitmask->isSetBitByShiftOffset(2));
    }

    public function testShiftOffsetOutOfRange()
    {
        $bitmask = new BitMask(static::WRITE, 2);
        try {
            $bitmask->setBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            $this->assertSame('2', $exception->getMessage());
        }
        try {
            $bitmask->unsetBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            $this->assertSame('2', $exception->getMessage());
        }
        try {
            $bitmask->isSetBitByShiftOffset(2);
        } catch (OutOfRangeException $exception) {
            $this->assertSame('2', $exception->getMessage());
        }
    }
}
