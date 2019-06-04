<?php

namespace BitMaskTests\Doctrine\Types;

use BitMask\BitMask;
use BitMask\Doctrine\Types\BitMaskType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use PHPUnit\Framework\TestCase;

class BitMaskTypeTest extends TestCase
{
    /** @var BitMaskType $typeMock */
    private $typeMock;

    /** @var AbstractPlatform $platformMock */
    private $platformMock;

    protected function setUp()
    {
        parent::setUp();
        $typeBuilder = $this
            ->getMockBuilder(BitMaskType::class)
            ->disableOriginalConstructor()
            ->setMethods(null);

        $this->typeMock = $typeBuilder->getMock();
        $this->platformMock = $this->getMockForAbstractClass(AbstractPlatform::class);
    }

    public function testGetSQLDeclaration()
    {
        $mysqlPlatform = new MySqlPlatform();
        $postgrePlatform = new PostgreSqlPlatform();

        $mysqlDeclaration = $this->typeMock->getSQLDeclaration([], $mysqlPlatform);
        $this->assertSame('varbinary(255)', strtolower($mysqlDeclaration));

        $postgreDeclaration = $this->typeMock->getSQLDeclaration([], $postgrePlatform);
        $this->assertSame('bytea', strtolower($postgreDeclaration));
    }

    public function testConvertToPHPValue()
    {
        /** @var BitMask $result */
        $result = $this->typeMock->convertToPHPValue('3', $this->platformMock);

        $this->assertSame(3, $result->get());
        $this->assertInstanceOf(BitMask::class, $result);
    }

    public function testConvertToDatabaseValue()
    {
        $this->assertSame(1, $this->typeMock->convertToDatabaseValue(new BitMask(1), $this->platformMock));
        $this->assertSame(1, $this->typeMock->convertToDatabaseValue(1, $this->platformMock));
        $this->assertNull($this->typeMock->convertToDatabaseValue(null, $this->platformMock));
    }

    public function testGetName()
    {
        $this->assertSame(BitMaskType::BITMASK, $this->typeMock->getName());
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->typeMock = null;
        $this->platformMock = null;
    }
}
