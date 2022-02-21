<?php

declare(strict_types=1);

namespace BitMask;

class IndexedBitMask extends BitMask
{
    public function getByIndex(int $index): bool
    {
        return $this->isSetBitByShiftOffset($index);
    }
}
