<?php
declare(strict_types=1);

namespace BitMask;

use BitMask\Util\Bits;
use InvalidArgumentException;

/**
 * Class IndexedBitMask
 * @package BitMask
 */
class IndexedBitMask extends BitMask
{
    /**
     * IndexedBitMask constructor.
     * @inheritDoc
     */
    public function __construct(?int $mask = null, ?int $bitsCount = null)
    {
        parent::__construct($mask, $bitsCount);
    }

    /**
     * @param int $index
     * @return bool
     */
    public function getByIndex(int $index): bool
    {
        return $this->isSetBitByShiftOffset($index);
    }
}
