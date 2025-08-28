<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\EnumBitMask;
use BitMask\Exception\OutOfRangeException;
use BitMask\Exception\UnknownEnumException;
use BitMask\Tests\fixtures\Enum\BackedString;
use BitMask\Tests\fixtures\Enum\EmptyEnum;
use BitMask\Tests\fixtures\Enum\Permissions;
use BitMask\Tests\fixtures\Enum\RandomBackedInt;
use BitMask\Tests\fixtures\Enum\Unknown;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

final class EnumBitMaskTest extends TestCase
{
    public function testNotAnEnum(): void
    {
        $this->expectException(UnknownEnumException::class);
        new EnumBitMask(self::class);
    }

    public function testUnknownEnum(): void
    {
        $bitmask = new EnumBitMask(Permissions::class);
        $this->expectException(UnknownEnumException::class);
        $bitmask->set(Unknown::Case);
    }

    public function testConstructWithDefaultMask(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class);
        assertSame(0, $enumBitmask->get());
    }

    public function testGet(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 8);
        assertSame(8, $enumBitmask->get());
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->has(Unknown::Case);
    }

    public function testSetOutOfRange(): void
    {
        new EnumBitMask(Permissions::class, 15);
        $this->expectException(OutOfRangeException::class);
        new EnumBitMask(Permissions::class, 16);
    }

    public function testIsSet(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        assertTrue($enumBitmask->has(Permissions::Create));
        assertTrue($enumBitmask->has(Permissions::Read));
        assertFalse($enumBitmask->has(Permissions::Update));
        assertFalse($enumBitmask->has(Permissions::Delete));
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->has(Unknown::Case);
    }

    public function testSetUnset(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        $enumBitmask->remove(Permissions::Create, Permissions::Read);
        assertFalse($enumBitmask->has(Permissions::Create));
        assertFalse($enumBitmask->has(Permissions::Read));
        assertFalse($enumBitmask->has(Permissions::Read, Permissions::Update));
        assertSame(0, $enumBitmask->get());
        $enumBitmask->set(Permissions::Update, Permissions::Delete);
        assertTrue($enumBitmask->has(Permissions::Update));
        assertTrue($enumBitmask->has(Permissions::Delete));
        assertTrue($enumBitmask->has(Permissions::Update, Permissions::Delete));
        assertSame(12, $enumBitmask->get());
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->remove(Unknown::Case);
    }
    public function testSetTwice(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        assertTrue($enumBitmask->has(Permissions::Create));
        $enumBitmask->set(Permissions::Create);
        assertSame(3, $enumBitmask->get());
        assertTrue($enumBitmask->has(Permissions::Create));
        $enumBitmask->set(Permissions::Create, Permissions::Read);
        assertTrue($enumBitmask->has(Permissions::Create));
        assertTrue($enumBitmask->has(Permissions::Read));
        assertSame(3, $enumBitmask->get());
        $enumBitmask->set(Permissions::Update);
        assertTrue($enumBitmask->has(Permissions::Update));
        assertSame(7, $enumBitmask->get());
    }

    public function testRemoveTwice(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        assertTrue($enumBitmask->has(Permissions::Create));
        $enumBitmask->remove(Permissions::Create);
        assertSame(2, $enumBitmask->get());
        $enumBitmask->remove(Permissions::Create);
        assertSame(2, $enumBitmask->get());
        $enumBitmask->remove(Permissions::Create, Permissions::Read);
        assertSame(0, $enumBitmask->get());

        $enumBitmask->set(...Permissions::cases());
        foreach (Permissions::cases() as $case) {
            $enumBitmask->remove($case);
            $enumBitmask->remove($case);
        }
        assertSame(0, $enumBitmask->get());
    }

    public function testBackedStringEnum(): void
    {
        $backedStringEnumBitmask = new EnumBitMask(BackedString::class, 3);
        assertTrue($backedStringEnumBitmask->has(BackedString::Create, BackedString::Read));
        assertFalse($backedStringEnumBitmask->has(BackedString::Update, BackedString::Delete));
        $backedStringEnumBitmask->remove(BackedString::Create, BackedString::Read);
        $backedStringEnumBitmask->set(BackedString::Update, BackedString::Delete);
        assertFalse($backedStringEnumBitmask->has(BackedString::Create, BackedString::Read));
        assertTrue($backedStringEnumBitmask->has(BackedString::Update, BackedString::Delete));
    }

    public function testCreateFactory(): void
    {
        $enumBitmask = EnumBitMask::create(Permissions::class);
        assertSame(0, $enumBitmask->get());

        $enumBitmask = EnumBitMask::create(Permissions::class, Permissions::Create);
        assertSame(1, $enumBitmask->get());

        $enumBitmask = EnumBitMask::create(Permissions::class, Permissions::Delete);
        assertSame(8, $enumBitmask->get());

        $enumBitmask = EnumBitMask::create(Permissions::class, Permissions::Delete, Permissions::Create);
        assertSame(9, $enumBitmask->get());

        $enumBitmask = EnumBitMask::create(Permissions::class, Permissions::Create, Permissions::Delete);
        assertSame(9, $enumBitmask->get());


        $this->expectException(UnknownEnumException::class);
        EnumBitMask::create(Permissions::class, Unknown::Case);
    }

    public function testNoneFactory(): void
    {
        $enumBitmask = EnumBitMask::none(Permissions::class);
        assertSame(0, $enumBitmask->get());
    }

    public function testAllFactory(): void
    {
        $enumBitmask = EnumBitMask::all(Permissions::class);
        assertSame(15, $enumBitmask->get());
    }

    public function testWithoutFactory(): void
    {
        $enumBitmask = EnumBitMask::without(Permissions::class, Permissions::Delete);
        assertSame(7, $enumBitmask->get());
    }

    public function testIsIntBackedEnumRandom(): void
    {
        // random backed int
        $backedIntEnumBitmask = new EnumBitMask(RandomBackedInt::class, 17);
        assertTrue($backedIntEnumBitmask->has(RandomBackedInt::Bit1, RandomBackedInt::Bit3));
        assertFalse($backedIntEnumBitmask->has(RandomBackedInt::Bit2));
    }

    public function testIsIntBackedEnumRandom2(): void
    {
        // random backed int
        $enumBitmask = new EnumBitMask(RandomBackedInt::class);
        $enumBitmask->set(RandomBackedInt::Bit3, RandomBackedInt::Bit4);
        assertTrue($enumBitmask->has(RandomBackedInt::Bit3, RandomBackedInt::Bit4));
    }

    public function testWithNormalUnitEnum(): void
    {
        $bitmask = new EnumBitMask(Permissions::class, 1);
        $this->assertInstanceOf(EnumBitMask::class, $bitmask);
        $this->assertTrue($bitmask->has(Permissions::Create));
        $this->assertFalse($bitmask->has(Permissions::Delete));
        $bitmask->set(Permissions::Delete);
        $this->assertTrue($bitmask->has(Permissions::Delete));
    }

    public function testWithEmptyEnum(): void
    {
        $bitmask = new EnumBitMask(EmptyEnum::class);
        $this->assertInstanceOf(EnumBitMask::class, $bitmask);
        $this->assertSame(0, $bitmask->get());
    }
}
