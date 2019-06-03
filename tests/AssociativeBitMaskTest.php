<?php

namespace BitMask\Tests;

use BitMask\AssociativeBitMask;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssociativeBitMaskTest extends TestCase
{
    public function testAssociativeBitMask()
    {
        $bitmask = new AssociativeBitMask(['first']);
        $this->assertInstanceOf(AssociativeBitMask::class, $bitmask);
        $this->expectExceptionObject(new InvalidArgumentException('Keys must be non empty'));
        $bitmask = new AssociativeBitMask([]);
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
        $bitmask = new AssociativeBitMask(['p1', 'p2', 'p4'], 5);
        /** __call */
        $this->assertTrue($bitmask->isP1());
        $this->assertFalse($bitmask->isP2());
        $this->assertTrue($bitmask->isP4());
        /** need catch exception */
        try {
            $this->assertFalse($bitmask->isP8());
        } catch (Exception $exception) {
        }


        /** __get */
        $this->assertTrue($bitmask->p1);
        $this->assertFalse($bitmask->p2);
        $this->assertTrue($bitmask->p4);
        /** need catch exception */
        try {
            $this->assertFalse($bitmask->p8());
        } catch (Exception $exception) {
        }

        /** __set */
        $bitmask->p1 = false;
        $this->assertFalse($bitmask->p1);
        $bitmask->p2 = true;
        $this->assertTrue($bitmask->p2);
        $bitmask->p4 = false;
        $this->assertFalse($bitmask->p4);
        /** need catch exception */
        try {
            $bitmask->p8 = true;
        } catch (Exception $exception) {
        }

        /** __isset */
        $this->assertFalse(isset($bitmask->p1));
        $this->assertTrue(isset($bitmask->p2));
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
        $this->assertEquals(0, $bitmask->get());
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
        $bitmask = new AssociativeBitMask(['first']);
        $bitmask->setBit(8);
        $this->assertTrue($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->setBit(3);
        $this->assertEquals(8, $bitmask->get());
    }

    public function testUnsetBit()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k3', 'k4']);
        $bitmask->setBit(8);
        $bitmask->unsetBit(8);
        $this->assertFalse($bitmask->isSetBit(8));
        $this->expectExceptionObject(new InvalidArgumentException('Argument must be a single bit'));
        $bitmask->unsetBit(3);
        $this->assertEquals(0, $bitmask->get());
    }

    public function testJsonSerialize()
    {
        $bitmask = new AssociativeBitMask(['k1', 'k2', 'k3', 'k4']);
        $bitmask->set(9);
        $jsonArray = $bitmask->jsonSerialize();
        $this->assertSame(['k1' => true, 'k2' => false, 'k3' => false, 'k4' => true], $jsonArray);
    }
}
