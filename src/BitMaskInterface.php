<?php
declare(strict_types=1);

namespace BitMask;

/**
 * Interface BitMaskInterface
 * @package BitMask
 */
interface BitMaskInterface
{
    /**
     * @return int
     */
    public function get(): int;

    /**
     * @param int $mask
     */
    public function set(int $mask): void;

    /**
     *
     */
    public function unset(): void;

    /**
     * @param int $mask
     * @return bool
     */
    public function isSet(int $mask): bool;

    /**
     * @param int $bit
     * @param bool $state
     */
    public function setBit(int $bit, bool $state = true): void;

    /**
     * @param int $bit
     */
    public function unsetBit(int $bit): void;

    /**
     * @param int $bit
     * @return bool
     */
    public function isSetBit(int $bit): bool;
}
