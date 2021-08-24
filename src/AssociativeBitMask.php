<?php
declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Util\Bits;
use InvalidArgumentException;
use LogicException;

/**
 * Class AssociativeBitMask
 * @package BitMask
 */
class AssociativeBitMask extends IndexedBitMask
{
    /** @var array<int, string> $keys */
    protected $keys;

    /**
     * AssociativeBitMask constructor.
     * @param int|null $mask
     * @param int|null $bitsCount
     * @param array<int, string>|null $keys
     *
     * @see https://www.php.net/manual/en/language.variables.basics.php
     * @todo check keys. Must be valid PHP identifier name ^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$
     */
    public function __construct(?int $mask = null, ?int $bitsCount = null, ?array $keys = [])
    {
        if (empty($keys)) {
            throw new InvalidArgumentException('Third argument "$keys" must be non empty array');
        }
        if ($bitsCount !== count($keys)) {
            throw new InvalidArgumentException('Second argument "$bitsCount" must be equal to $keys array size');
        }
        $this->keys = $keys;
        parent::__construct($mask);
    }

    /**
     * @param string $key
     * @return bool
     */
    final public function getByKey(string $key): bool
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new InvalidArgumentException(sprintf('Unknown key "%s"', $key));
        }
        return $this->getByIndex(intval($index));
    }

    /**
     * @param string $method
     * @param mixed[] $args
     * @return bool
     */
    final public function __call(string $method, array $args): bool
    {
        if (!method_exists($this, $method) && strpos($method, 'is') === 0) {
            $key = lcfirst(substr($method, 2));
            return $this->getByKey($key);
        }
        throw new LogicException('Magic call should be related only for keys');
    }

    /**
     * @param string $key
     * @return bool
     */
    final public function __get(string $key): bool
    {
        return $this->getByKey($key);
    }

    /**
     * @param string $key
     * @param bool $isSet
     * @throws NotSingleBitException
     */
    final public function __set(string $key, bool $isSet): void
    {
        $state = $this->getByKey($key);
        if ($state === $isSet) {
            return;
        }
        /** @var int $index */
        $index = array_search($key, $this->keys);
        $bit = Bits::indexToBit($index);
        if ($isSet) {
            $this->setBit($bit);
        } else {
            $this->unsetBit($bit);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    final public function __isset(string $key)
    {
        return $this->getByKey($key);
    }

    /**
     * @return bool[]
     */
    public function jsonSerialize(): array
    {
        $array = [];
        foreach ($this->keys as $index => $key) {
            $array[$key] = $this->getByIndex($index);
        }
        return $array;
    }
}
