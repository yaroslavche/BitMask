<?php

namespace BitMask\Tests\fixtures\Enum;

enum RandomBackedInt: int
{
    case Bit1 = 1 << 0;
    case Bit2 = 1 << 1;
    case Bit4 = 1 << 3;
    case Bit3 = 1 << 4;
}
