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
     * Set BitMask integer value.
     *
     * $mask = new BitMask();
     * $mask->set(5);
     * $mask->get(); => (int) 5
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function set(int $mask): void;

    /**
     * Unset BitMask value.
     *
     * $mask = new BitMask(5);
     * $mask->unset();
     * $mask->get(); => 0
     */
    public function unset(): void;

    /**
     * Check if $mask is set in BitMask integer value.
     *
     * $mask = new BitMask(1 << 1);
     * $mask->isSet(1); => false
     * $mask->isSet(2); => true
     */
    public function isSet(int $mask): bool;

    /**
     * Set $bit in BitMask integer value.
     *
     * $mask = new BitMask(0);
     * $mask->setBit(1);
     * $mask->setBit(4);
     * $mask->get(); => 5
     * $mask->setBit(3) => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function setBits(int ...$bits): void;

    /**
     * Unset $bit in BitMask integer value.
     *
     * $mask = new BitMask(7);
     * $mask->unsetBit(2);
     * $mask->get(); => 5
     * $mask->unsetBit(5) => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function unsetBits(int ...$bits): void;

    /**
     * Check if $bit is set in BitMask integer value.
     *
     * $mask = new BitMask(5);
     * $mask->isSetBit(1); => true
     * $mask->isSetBit(2); => false
     * $mask->isSetBit(4); => true
     * $mask->unsetBit(5); => NotSingleBitException
     *
     * @throws NotSingleBitException
     */
    public function isSetBits(int ...$bits): bool;

    /**
     * Set bit in shift offset of BitMask integer value.
     *
     * $mask = new BitMask();
     * $mask->setBitByOffset(0);
     * $mask->get(); => 1
     * $mask->setBitByOffset(1);
     * $mask->get(); => 3
     * $mask->setBitByOffset(2);
     * $mask->get(); => 7
     * $mask->setBitByOffset(-1) => OutOfRangeException *
     * * Or possible map in inverse direction with $inverseMask << abs($shiftOffset), but seems weird and not needed
     *
     * @throws OutOfRangeException
     */
    public function setBitByShiftOffset(int $shiftOffset): void;

    /**
     * Unset bit in shift offset of BitMask integer value.
     *
     * $mask = new BitMask(5);
     * $mask->unsetBitByOffset(0);
     * $mask->get(); => 4
     * $mask->unsetBitByOffset(1); // wasn't set
     * $mask->get(); => 4
     * $mask->unsetBitByOffset(2);
     * $mask->get(); => 0
     * $mask->unsetBitByOffset(-1) => OutOfRangeException *
     *
     * @throws OutOfRangeException
     */
    public function unsetBitByShiftOffset(int $shiftOffset): void;

    /**
     * Check if $bit is set in shift offset of BitMask integer value.
     *
     * $mask = new BitMask(2);
     * $mask->isSetBitByOffset(0); => false
     * $mask->isSetBitByOffset(1); => true
     * $mask->isSetBitByOffset(2); => false
     * $mask->isSetBitByOffset(-1) => OutOfRangeException *
     *
     * @throws OutOfRangeException
     */
    public function isSetBitByShiftOffset(int $shiftOffset): bool;
}
