<?php
declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use OutOfRangeException;

/**
 * Interface BitMaskInterface
 * @package BitMask
 */
interface BitMaskInterface
{
    /**
     * BitMaskInterface constructor.
     *
     * @param int|null $mask
     * @param int|null $bitsCount If not null - restricts boundaries
     */
    public function __construct(?int $mask = null, ?int $bitsCount = null);

    /**
     * Get BitMask integer value.
     *
     * $mask = new BitMask(1 << 0 | 1 << 1 | 1 << 2);
     * $mask->get(); => (int) 7
     *
     * @return int|null
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function get(): ?int;

    /**
     * Set BitMask integer value.
     *
     * $mask = new BitMask();
     * $mask->set(5);
     * $mask->get(); => (int) 5
     *
     * @param int $mask
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function set(int $mask): void;

    /**
     * Unset BitMask value.
     *
     * $mask = new BitMask(5);
     * $mask->unset();
     * $mask->get(); => null
     */
    public function unset(): void;

    /**
     * Check if $mask is set in BitMask integer value.
     *
     * $mask = new BitMask(1 << 1);
     * $mask->isSet(1); => false
     * $mask->isSet(2); => true
     *
     * @param int $mask
     * @return bool
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
     * @param int $bit
     * @throws NotSingleBitException
     */
    public function setBit(int $bit): void;

    /**
     * Unset $bit in BitMask integer value.
     *
     * $mask = new BitMask(7);
     * $mask->unsetBit(2);
     * $mask->get(); => 5
     * $mask->unsetBit(5) => NotSingleBitException
     *
     * @param int $bit
     * @throws NotSingleBitException
     */
    public function unsetBit(int $bit): void;

    /**
     * Check if $bit is set in BitMask integer value.
     *
     * $mask = new BitMask(5);
     * $mask->isSetBit(1); => true
     * $mask->isSetBit(2); => false
     * $mask->isSetBit(4); => true
     * $mask->unsetBit(5); => NotSingleBitException
     *
     * @param int $bit
     * @return bool
     * @throws NotSingleBitException
     */
    public function isSetBit(int $bit): bool;

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
     * @param int $shiftOffset
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
     * @param int $shiftOffset
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
     * @param int $shiftOffset
     * @return bool
     * @throws OutOfRangeException
     */
    public function isSetBitByShiftOffset(int $shiftOffset): bool;
}
