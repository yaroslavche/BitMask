<?php

namespace BitMask\Tests\fixtures\Enum;

enum BackedString: string
{
    case Create = 'Create';
    case Read = 'Read';
    case Update = 'Update';
    case Delete = 'Delete';
}
