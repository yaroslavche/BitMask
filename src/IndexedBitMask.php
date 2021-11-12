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
    public function getByIndex(int $index): bool
    {
        return $this->isSetBitByShiftOffset($index);
    }
}
