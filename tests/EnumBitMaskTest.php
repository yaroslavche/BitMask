<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\EnumBitMask;
use BitMask\Exception\OutOfRangeException;
use BitMask\Exception\UnknownEnumException;
use BitMask\Tests\fixtures\Enum\BackedInt;
use BitMask\Tests\fixtures\Enum\BackedString;
use BitMask\Tests\fixtures\Enum\Permissions;
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
        $bitmask->setEnumBits(Unknown::Case);
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
        $enumBitmask->isSetEnumBits(Unknown::Case);
    }

    public function testSetOutOfRange(): void
    {
        $this->expectException(OutOfRangeException::class);
        new EnumBitMask(Permissions::class, 9);
    }

    public function testIsSet(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        assertTrue($enumBitmask->isSetEnumBits(Permissions::Create));
        assertTrue($enumBitmask->isSetEnumBits(Permissions::Read));
        assertFalse($enumBitmask->isSetEnumBits(Permissions::Update));
        assertFalse($enumBitmask->isSetEnumBits(Permissions::Delete));
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->isSetEnumBits(Unknown::Case);
    }

    public function testSetUnset(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, 3);
        $enumBitmask->unsetEnumBits(Permissions::Create, Permissions::Read);
        assertFalse($enumBitmask->isSetEnumBits(Permissions::Create));
        assertFalse($enumBitmask->isSetEnumBits(Permissions::Read));
        assertFalse($enumBitmask->isSetEnumBits(Permissions::Read, Permissions::Update));
        assertSame(0, $enumBitmask->get());
        $enumBitmask->setEnumBits(Permissions::Update, Permissions::Delete);
        assertTrue($enumBitmask->isSetEnumBits(Permissions::Update));
        assertTrue($enumBitmask->isSetEnumBits(Permissions::Delete));
        assertTrue($enumBitmask->isSetEnumBits(Permissions::Update, Permissions::Delete));
        assertSame(12, $enumBitmask->get());
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->unsetEnumBits(Unknown::Case);
    }

    public function testBackedEnum(): void
    {
        // backed string
        $backedStringEnumBitmask = new EnumBitMask(BackedString::class, 3);
        assertTrue($backedStringEnumBitmask->isSetEnumBits(BackedString::Create, BackedString::Read));
        assertFalse($backedStringEnumBitmask->isSetEnumBits(BackedString::Update, BackedString::Delete));
        $backedStringEnumBitmask->unsetEnumBits(BackedString::Create, BackedString::Read);
        $backedStringEnumBitmask->setEnumBits(BackedString::Update, BackedString::Delete);
        assertFalse($backedStringEnumBitmask->isSetEnumBits(BackedString::Create, BackedString::Read));
        assertTrue($backedStringEnumBitmask->isSetEnumBits(BackedString::Update, BackedString::Delete));
        // backed int
        $backedIntEnumBitmask = new EnumBitMask(BackedInt::class, 3);
        assertTrue($backedIntEnumBitmask->isSetEnumBits(BackedInt::Create, BackedInt::Read));
        assertFalse($backedIntEnumBitmask->isSetEnumBits(BackedInt::Update, BackedInt::Delete));
        $backedIntEnumBitmask->unsetEnumBits(BackedInt::Create, BackedInt::Read);
        $backedIntEnumBitmask->setEnumBits(BackedInt::Update, BackedInt::Delete);
        assertFalse($backedIntEnumBitmask->isSetEnumBits(BackedInt::Create, BackedInt::Read));
        assertTrue($backedIntEnumBitmask->isSetEnumBits(BackedInt::Update, BackedInt::Delete));
    }
}
