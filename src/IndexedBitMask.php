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
     * @var array $map
     */
    protected $map;

    /**
     * IndexedBitMask constructor.
     * @param int $mask
     */
    public function __construct(int $mask = null)
    {
        $this->map = [];
        parent::__construct($mask);
    }

    /**
     * @param int $mask
     */
    public function set(int $mask = null): void
    {
        parent::set($mask);
        for ($index = 0; $index < Bits::getMSB($mask); $index++) {
            $this->map[$index] = $this->isSetBit(pow(2, $index));
        }
    }

    /**
     * @param int $bit
     * @param bool $state
     */
    public function setBit(int $bit, bool $state = null): void
    {
        parent::setBit($bit, $state);
        $index = Bits::bitToIndex($bit);
        $this->map[$index] = $state ?? true;
    }

    /**
     * @param int $bit
     */
    public function unsetBit(int $bit): void
    {
        $this->setBit($bit, false);
    }

    /**
     * @param int $index
     * @return bool
     */
    public function getByIndex(int $index): bool
    {
        if ($index < 0) {
            throw new InvalidArgumentException('Index (zero based) must be greater than or equal to zero');
        }
        if (!isset($this->map[$index])) {
            throw new InvalidArgumentException('Index not exists in bitmask');
        }
        return $this->map[$index];
    }
}
