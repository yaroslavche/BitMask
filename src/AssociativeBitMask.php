<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\InvalidIndexException;
use BitMask\Exception\KeysMustBeSetException;
use BitMask\Exception\KeysSizeMustBeEqualBitsCountException;
use BitMask\Exception\MagicCallException;
use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\UnknownKeyException;
use BitMask\Util\Bits;

class AssociativeBitMask extends IndexedBitMask
{
    /** @var array<int, string> $keys */
    protected array $keys;

    /**
     * @param array<int, string>|null $keys
     * @throws KeysMustBeSetException
     * @throws KeysSizeMustBeEqualBitsCountException
     * @todo Must be valid PHP identifier name ^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$
     * @todo research and check keys. https://www.php.net/manual/en/language.variables.basics.php
     */
    public function __construct(?int $mask = null, ?int $bitsCount = null, ?array $keys = [])
    {
        if (empty($keys)) {
            throw new KeysMustBeSetException('Third argument "$keys" must be non empty array');
        }
        if ($bitsCount !== count($keys)) {
            throw new KeysSizeMustBeEqualBitsCountException('Second argument "$bitsCount" must be equal to $keys array size');
        }
        $this->keys = $keys;
        parent::__construct($mask, $bitsCount);
    }

    /** @throws UnknownKeyException */
    final public function getByKey(string $key): bool
    {
        $index = array_search($key, $this->keys);
        if ($index === false) {
            throw new UnknownKeyException($key);
        }
        return $this->getByIndex(intval($index));
    }

    /**
     * @param array<int, mixed> $args
     * @throws UnknownKeyException
     * @throws MagicCallException
     */
    final public function __call(string $method, array $args): bool
    {
        if (!method_exists($this, $method) && str_starts_with($method, 'is')) {
            $key = lcfirst(substr($method, 2));
            return $this->getByKey($key);
        }
        throw new MagicCallException('Magic call should be related only for keys');
    }

    /** @throws UnknownKeyException */
    final public function __get(string $key): bool
    {
        return $this->getByKey($key);
    }

    /**
     * @throws UnknownKeyException
     * @throws InvalidIndexException
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

    /** @throws UnknownKeyException */
    final public function __isset(string $key): bool
    {
        return $this->getByKey($key);
    }

    /** @return bool[] */
    final public function jsonSerialize(): array
    {
        $array = [];
        foreach ($this->keys as $index => $key) {
            $array[$key] = $this->getByIndex($index);
        }
        return $array;
    }
}
