<?php
declare(strict_types=1);

namespace BitMask\Tests;

use BitMask\AssociativeBitMask;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

class AssociativeBitMaskTest extends TestCase
{
    public function testEmptyKeys()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Third argument "$keys" must be non empty array');
        new AssociativeBitMask(7, 3, []);
    }

    public function testBitsNotEqualKeys()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Second argument "$bitsCount" must be equal to $keys array size');
        new AssociativeBitMask(7, 3, ['test']);
    }

    public function testGetByKey()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertTrue($bitmask->getByKey('readable'));
        $this->assertTrue($bitmask->getByKey('writable'));
        $this->assertTrue($bitmask->getByKey('executable'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown key "unknown"');
        $bitmask->getByKey('unknown');
    }

    public function testMagicGet()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertTrue($bitmask->readable);
        $this->assertTrue($bitmask->writable);
        $this->assertTrue($bitmask->executable);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown key "unknown"');
        $bitmask->unknown;
    }


    public function testMagicCall()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertTrue($bitmask->isReadable());
        $this->assertTrue($bitmask->isWritable());
        $this->assertTrue($bitmask->isExecutable());
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Magic call should be related only for keys');
        $bitmask->invalidMethodName();
    }

    public function testMagicIsSet()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertTrue(isset($bitmask->readable));
        $this->assertTrue(isset($bitmask->writable));
        $this->assertTrue(isset($bitmask->executable));
    }


    public function testMagicSet()
    {
        $bitmask = new AssociativeBitMask(5, 3, ['readable', 'writable', 'executable']);
        $bitmask->readable = false;
        $this->assertFalse($bitmask->readable);
        $bitmask->writable = true;
        $this->assertTrue($bitmask->writable);
        $bitmask->executable = false;
        $this->assertFalse($bitmask->executable);
        $bitmask->executable = false; // AssociativeBitMask.php:80
        try {
            $bitmask->unknownKey = true;
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Unknown key "unknownKey"', $exception->getMessage());
        }
    }

    public function testJsonSerialize()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertSame(['readable' => true, 'writable' => true, 'executable' => true], $bitmask->jsonSerialize());
    }

    public function testIssue18()
    {
        $bitmask = new AssociativeBitMask(7, 3, ['readable', 'writable', 'executable']);
        $this->assertTrue($bitmask->isReadable());
        $this->assertTrue($bitmask->isWritable());
        $this->assertTrue($bitmask->isExecutable());
        $bitmask->set(1);
        $this->assertTrue($bitmask->isReadable());
        $this->assertFalse($bitmask->isWritable());
        $this->assertFalse($bitmask->isExecutable());
    }
}
