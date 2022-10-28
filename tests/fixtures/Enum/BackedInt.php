<?php

namespace BitMask\Tests\fixtures\Enum;

enum BackedInt: int
{
    case Create = 1;
    case Read = 2;
    case Update = 3;
    case Delete = 4;
}
