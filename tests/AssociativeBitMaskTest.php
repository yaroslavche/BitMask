<?php

declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\AssociativeBitMask;
use BitMask\Exception\KeysMustBeSetException;
use BitMask\Exception\KeysSizeMustBeEqualBitsCountException;
use BitMask\Exception\MagicCallException;
use BitMask\Exception\UnknownKeyException;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class AssociativeBitMaskTest extends TestCase
{
    public function testEmptyKeys(): void
    {
        $this->expectException(KeysMustBeSetException::class);
        $this->expectExceptionMessage('Third argument "$keys" must be non empty array');
        new AssociativeBitMask(7, 3, []);
    }

    public function testBitsNotEqualKeys(): void
    {
        $this->expectException(KeysSizeMustBeEqualBitsCountException::class);
        $this->expectExceptionMessage('Second argument "$bitsCount" must be equal to $keys array size');
        new AssociativeBitMask(7, 3, ['test']);
    }

    public function testGetByKey(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertTrue($bitmask->getByKey('readable'));
        assertTrue($bitmask->getByKey('writable'));
        assertTrue($bitmask->getByKey('executable'));
        $this->expectException(UnknownKeyException::class);
        $this->expectExceptionMessage('unknown');
        $bitmask->getByKey('unknown');
    }

    public function testMagicGet(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertTrue($bitmask->readable);
        assertTrue($bitmask->writable);
        assertTrue($bitmask->executable);
        $this->expectException(UnknownKeyException::class);
        $this->expectExceptionMessage('unknown');
        $bitmask->unknown;
    }


    public function testMagicCall(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertTrue($bitmask->isReadable());
        assertTrue($bitmask->isWritable());
        assertTrue($bitmask->isExecutable());
        $this->expectException(MagicCallException::class);
        $this->expectExceptionMessage('Magic call should be related only for keys');
        $bitmask->invalidMethodName();
    }

    public function testMagicIsSet(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertTrue(isset($bitmask->readable));
        assertTrue(isset($bitmask->writable));
        assertTrue(isset($bitmask->executable));
    }


    public function testMagicSet(): void
    {
        $bitmask = new AssociativeBitMask(5, 3, ['readable', 'writable', 'executable']);
        $bitmask->readable = false;
        assertFalse($bitmask->readable);
        $bitmask->writable = true;
        assertTrue($bitmask->writable);
        $bitmask->executable = false;
        assertFalse($bitmask->executable);
        $bitmask->executable = false;
        try {
            $bitmask->unknownKey = true;
        } catch (UnknownKeyException $exception) {
            assertSame('unknownKey', $exception->getMessage());
        }
    }

    public function testJsonSerialize(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertSame(['readable' => true, 'writable' => true, 'executable' => true], $bitmask->jsonSerialize());
    }

    public function testIssue18(): void
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        assertTrue($bitmask->isReadable());
        assertTrue($bitmask->isWritable());
        assertTrue($bitmask->isExecutable());
        $bitmask->set(1);
        assertTrue($bitmask->isReadable());
        assertFalse($bitmask->isWritable());
        assertFalse($bitmask->isExecutable());
    }
}
