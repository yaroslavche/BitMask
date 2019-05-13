<?php
declare(strict_types=1);

namespace BitMask;

use BitMask\Util\Bits;
use InvalidArgumentException;

/**
 * Class BitMask
 * @package BitMask
 */
class BitMask implements BitMaskInterface
{
    /**
     * @var int $storage
     */
    private $storage;

    /**
     * BitMask constructor.
     * @param int|null $mask
     */
    public function __construct(?int $mask = null)
    {
        $this->set($mask ?? 0);
    }

    /**
     * @param int|null $mask
     * @return BitMask
     */
    public static function init(?int $mask = null): self
    {
        return new static($mask);
    }

    /**
     * @return int
     */
    public function get(): int
    {
        return $this->storage;
    }

    /**
     * @param int $mask
     */
    public function set(int $mask = null): void
    {
        $this->storage = $mask ?? 0;
    }

    /**
     *
     */
    public function unset(): void
    {
        $this->storage = 0;
    }

    /**
     * @param int $mask
     * @return bool
     */
    public function isSet(int $mask): bool
    {
        return $this->storage >= $mask && $this->storage & $mask;
    }

    /**
     * @inheritdoc
     */
    public function setBit(int $bit, bool $state = null): void
    {
        $state = $state ?? true;
        if (!Bits::isSingleBit($bit)) {
            throw new InvalidArgumentException('Argument must be a single bit');
        }
        if ($state) {
            $this->storage |= $bit;
        } else {
            $this->storage ^= $bit;
            /**
             * maybe it's just obviously - one operation vs. two. But work almost same
             * @see ./vendor/bin/phpbench run benchmarks/UnsetBitBench.php --report=default
             */
            // $this->storage &= ~$bit;
        }
    }

    /**
     * @inheritdoc
     */
    public function unsetBit(int $bit): void
    {
        $this->setBit($bit, false);
    }

    /**
     * @inheritdoc
     */
    public function isSetBit(int $bit): bool
    {
        if (!Bits::isSingleBit($bit)) {
            throw new InvalidArgumentException('Argument must be a single bit');
        }
        return $this->isSet($bit);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->storage;
    }

    /**
     * @param int $mask
     * @return bool
     */
    public function __invoke(int $mask): bool
    {
        return $this->isSet($mask);
    }
}
