<?php

namespace BitMask\Doctrine\Types;

use BitMask\BitMask;
use BitMask\BitMaskInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Bitmask datatype.
 */
class BitMaskType extends Type
{
    const BITMASK = Type::BINARY;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): BitMaskInterface
    {
        $phpValue = new BitMask($value);
        return $phpValue;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BitMaskInterface) {
            return $value->get();
        } elseif (is_int($value)) {
            return $value;
        } else {
            return null;
        }
    }

    public function getName()
    {
        return self::BITMASK;
    }
}
