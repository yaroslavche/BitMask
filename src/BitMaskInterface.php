<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\OutOfRangeException;

interface BitMaskInterface
{
    /**
     * $mask = new BitMask(0b111);
     * $mask->get(); => (int) 7
     */
    public function get(): int;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask();
     * $mask->set(5);
     * $mask->get(); => (int) 5
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function set(int $mask): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(5);
     * $mask->unset();
     * $mask->get(); => 0
     */
    public function unset(): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(1 << 1);
     * $mask->isSet(1); => false
     * $mask->isSet(2); => true
     */
    public function isSet(int $mask): bool;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask();
     * $mask->setBits(1, 4);
     * $mask->get(); => 5
     * $mask->setBit(3) => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function setBits(int ...$bits): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(7);
     * $mask->unsetBits(2);
     * $mask->get(); => 5
     * $mask->unsetBit(5) => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function unsetBits(int ...$bits): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(5);
     * $mask->isSetBits(1, 4); => true
     * $mask->isSetBits(2); => false
     * $mask->isSetBits(5); => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function isSetBits(int ...$bits): bool;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask();
     * $mask->setBitByOffset(0);
     * $mask->get(); => 1
     * $mask->setBitByOffset(1);
     * $mask->get(); => 3
     * $mask->setBitByOffset(2);
     * $mask->get(); => 7
     * $mask->setBitByOffset(-1) => OutOfRangeException
     *
     * @throws OutOfRangeException
     */
    public function setBitByShiftOffset(int $shiftOffset): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(5);
     * $mask->unsetBitByOffset(0);
     * $mask->get(); => 4
     * $mask->unsetBitByOffset(1); // wasn't set
     * $mask->get(); => 4
     * $mask->unsetBitByOffset(2);
     * $mask->get(); => 0
     * $mask->unsetBitByOffset(-1) => OutOfRangeException
     *
     * @throws OutOfRangeException
     */
    public function unsetBitByShiftOffset(int $shiftOffset): void;

    /**
     * @deprecated would be dropped from interface
     * $mask = new BitMask(2);
     * $mask->isSetBitByOffset(0); => false
     * $mask->isSetBitByOffset(1); => true
     * $mask->isSetBitByOffset(2); => false
     * $mask->isSetBitByOffset(-1) => OutOfRangeException
     *
     * @throws OutOfRangeException
     */
    public function isSetBitByShiftOffset(int $shiftOffset): bool;
}
