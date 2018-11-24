<?php
declare(strict_types=1);

namespace BitMask;

use BitMask\Util\Bits;

/**
 * Class IndexedBitMask
 * @package BitMask
 */
class IndexedBitMask extends BitMask
{
    /**
     * @var array
     * @todo add type in 7.3
     */
    protected $map;

    /**
     * IndexedBitMask constructor.
     * @param int $mask
     */
    public function __construct(int $mask = 0)
    {
        $this->map = [];
        parent::__construct($mask);
    }

    /**
     * @param int $mask
     * @throws \Exception
     */
    public function set(int $mask = 0): void
    {
        parent::set($mask);
        for ($index = 0; $index < Bits::getMSB($mask); $index++) {
            $this->map[$index] = $this->isSetBit(pow(2, $index));
        }
    }

    /**
     * @param int $bit
     * @param bool $state
     * @throws \Exception
     */
    public function setBit(int $bit, bool $state = true): void
    {
        parent::setBit($bit, $state);
        $index = Bits::bitToIndex($bit);
        $this->map[$index] = $state;
    }

    /**
     * @param int $bit
     * @throws \Exception
     */
    public function unsetBit(int $bit): void
    {
        $this->setBit($bit, false);
    }

    /**
     * @param int $index
     * @return bool|null
     */
    public function getByIndex(int $index = 0): ?bool
    {
        return isset($this->map[$index]) ? $this->map[$index] : null;
    }
}
