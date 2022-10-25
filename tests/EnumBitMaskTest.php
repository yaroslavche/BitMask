<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\EnumBitMask;
use BitMask\Exception\UnknownEnumException;
use BitMask\Tests\fixtures\Enum\Permissions;
use BitMask\Tests\fixtures\Enum\Unknown;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use const PHP_VERSION_ID;

final class EnumBitMaskTest extends TestCase
{
    protected function setUp(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('PHP ^8.1 only');
        }
    }

    public function testNotAnEnum(): void
    {
        $this->expectException(UnknownEnumException::class);
        new EnumBitMask(self::class);
    }

    public function testUnknownEnum(): void
    {
        $this->expectException(UnknownEnumException::class);
        new EnumBitMask(Permissions::class, Unknown::Case);
    }

    public function testGet(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, Permissions::Create, Permissions::Read);
        assertSame(3, $enumBitmask->get());
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->isSet(Unknown::Case);
    }

    public function testIsSet(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, Permissions::Create, Permissions::Read);
        assertTrue($enumBitmask->isSet(Permissions::Create));
        assertTrue($enumBitmask->isSet(Permissions::Read));
        assertFalse($enumBitmask->isSet(Permissions::Update));
        assertFalse($enumBitmask->isSet(Permissions::Delete));
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->isSet(Unknown::Case);
    }

    public function testSetUnset(): void
    {
        $enumBitmask = new EnumBitMask(Permissions::class, Permissions::Create, Permissions::Read);
        $enumBitmask->unset(Permissions::Create, Permissions::Read);
        assertFalse($enumBitmask->isSet(Permissions::Create));
        assertFalse($enumBitmask->isSet(Permissions::Read));
        assertFalse($enumBitmask->isSet(Permissions::Read, Permissions::Update));
        assertSame(0, $enumBitmask->get());
        $enumBitmask->set(Permissions::Update, Permissions::Delete);
        assertTrue($enumBitmask->isSet(Permissions::Update));
        assertTrue($enumBitmask->isSet(Permissions::Delete));
        assertTrue($enumBitmask->isSet(Permissions::Update, Permissions::Delete));
        assertSame(12, $enumBitmask->get());
        $this->expectException(UnknownEnumException::class);
        $enumBitmask->unset(Unknown::Case);
    }
}
