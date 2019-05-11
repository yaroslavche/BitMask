<?php
declare(strict_types=1);

namespace BitMask;

/**
 * Class BitMask
 * @package BitMask
 */
class BitMask implements BitMaskInterface
{
    /**
     * @var int $storage
     * @todo add type in 7.3
     */
    private $storage;

    /**
     * BitMask constructor.
     * @param int|null $mask
     */
    public function __construct(?int $mask = 0)
    {
        $this->set($mask ?? 0);
    }

    /**
     * @param int|null $mask
     * @return BitMask
     */
    public static function init(?int $mask = 0): self
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
    public function set(int $mask): void
    {
        $this->storage = $mask;
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
        $set = ($this->storage >= $mask) ? $this->storage & $mask : 0;
        return $set > 0;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function setBit(int $bit, bool $state = null): void
    {
        $state = $state ?? true;
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \InvalidArgumentException('Must be single bit');
        }
        if ($state) {
            $this->storage |= $bit;
        } else {
            // $this->storage &= ~$bit;
            $this->storage ^= $bit;
        }
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function unsetBit(int $bit): void
    {
        $this->setBit($bit, false);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function isSetBit(int $bit): bool
    {
        if (!Util\Bits::isSingleBit($bit)) {
            throw new \InvalidArgumentException('Must be single bit');
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
