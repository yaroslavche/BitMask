<?php

namespace BitMask\Tests;

use BitMask\AssociativeBitMask;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssociativeBitMaskTest extends TestCase
{
    public function testAssociativeBitMask()
    {
        $bitmask = new AssociativeBitMask(['first', 'second'], 3);
        $this->assertInstanceOf(AssociativeBitMask::class, $bitmask);
        $this->assertSame(['first' => true, 'second' => true], $bitmask->jsonSerialize());
        $this->assertSame(3, $bitmask->get());
        try {
            $bitmask = new AssociativeBitMask([]);
            $this->assertNull($bitmask);
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Keys must be non empty', $exception->getMessage());
        }
    }

    public function testGet()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * "[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*"
     */
    public function testGetByKey()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k4'], 5);
        $this->assertTrue($bitmask->getByKey('k1'));
        $this->assertFalse($bitmask->getByKey('k2'));
        $this->assertTrue($bitmask->getByKey('k4'));
        $this->expectExceptionMessageRegExp('/Unknown key "[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*"$/');
        $this->assertFalse($bitmask->getByKey('k8'));
    }

    public function testMagicMethods()
    {
        $bitmask = new AssociativeBitMask(['readable', 'writable', 'executable'], 5);
        /** __call */
        $this->assertTrue($bitmask->isReadable());
        $this->assertFalse($bitmask->isWritable());
        $this->assertTrue($bitmask->isExecutable());
        try {
            $result = $bitmask->isUnknownKey();
            $this->assertNull($result);
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Unknown key "unknownKey"', $exception->getMessage());
        }
        $this->assertNull($bitmask->unknownMethodName());

        /** __get */
        $this->assertTrue($bitmask->readable);
        $this->assertFalse($bitmask->writable);
        $this->assertTrue($bitmask->executable);
        try {
            $result = $bitmask->unknownKey;
            $this->assertNull($result);
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Unknown key "unknownKey"', $exception->getMessage());
        }

        /** __set */
        $bitmask->readable = false;
        $this->assertFalse($bitmask->readable);
        $bitmask->writable = true;
        $this->assertTrue($bitmask->writable);
        $bitmask->executable = false;
        $this->assertFalse($bitmask->executable);
        $bitmask->executable = false;
        try {
            $bitmask->unknownKey = true;
            $this->assertNull($result);
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Unknown key "unknownKey"', $exception->getMessage());
        }

        /** __isset */
        $this->assertFalse(isset($bitmask->readable));
        $this->assertTrue(isset($bitmask->writable));
    }

    /**
     * @todo check PHP_INT_MAX
     */
    public function testSet()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k4']);
        $bitmask->set(7);
        $this->assertEquals(7, $bitmask->get());
        $bitmask->set();
        $this->assertEquals(0, $bitmask->get());
        $this->expectExceptionMessageRegExp('/Invalid given mask "[\d+]". Maximum value for [\d+] keys is [\d+]$/');
        $bitmask->set(8);
    }

    /**
     * @todo check PHP_INT_MAX
     */
    public function testUnset()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $bitmask->unset();
        $this->assertEquals(0, $bitmask->get());
    }

    /**
     * @todo check PHP_INT_MAX
     */
    public function testIsSet()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k4'], 7);
        $this->assertTrue($bitmask->isSet(7));
        $bitmask->set(0);
        $this->assertFalse($bitmask->isSet(7));
    }

    /**
     * @todo not working properly
     */
    public function testIsSetBit()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k3', 'k4']);
        $this->assertFalse($bitmask->isSetBit(8));
//        $this->assertTrue($bitmask->isSetBit(4));
    }

    public function testSetBit()
    {
        $bitmask = new AssociativeBitMask(['r', 'w', 'x'], 1);
        $bitmask->setBit(4);
        $this->assertTrue($bitmask->isSetBit(4));
        $this->assertSame(5, $bitmask->get());
        try {
            $bitmask->setBit(3);
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('Argument must be a single bit', $exception->getMessage());
        }
    }

    public function testUnsetBit()
    {
        $bitmask = new AssociativeBitMask(['read', 'write', 'execute'], 7);
        $bitmask->unsetBit(1);
        $this->assertFalse($bitmask->isSetBit(1));
        $bitmask->unsetBit(2);
        $this->assertFalse($bitmask->isSetBit(2));
        $bitmask->unsetBit(4);
        $this->assertFalse($bitmask->isSetBit(4));
        $this->assertSame(0, $bitmask->get());
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }

    public function testJsonSerialize()
    {
        $bitmask = new AssociativeBitMask(['read', 'write', 'execute'], 7);
        $this->assertSame(['read' => true, 'write' => true, 'execute' => true], $bitmask->jsonSerialize());
    }
}
