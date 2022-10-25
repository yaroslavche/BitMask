<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\UnknownEnumException;
use UnitEnum;

class EnumBitMask
{
    private int $bitmask = 0;
    /** @var UnitEnum[] $keys */
    private array $keys = [];

    /**
     * @param class-string $maskEnum
     * @throws UnknownEnumException
     */
    public function __construct(
        private readonly string $maskEnum,
        UnitEnum ...$bits,
    ) {
        if (!is_subclass_of($this->maskEnum, UnitEnum::class)) {
            throw new UnknownEnumException('BitMask enum must be subclass of UnitEnum');
        }
        $this->keys = $this->maskEnum::cases();
        $this->set(...$bits);
    }

    public function get(): int
    {
        return $this->bitmask;
    }

    /** @throws UnknownEnumException */
    public function set(UnitEnum ...$bits): void
    {
        foreach ($bits as $bit) {
            if (!$this->isSet($bit)) {
                $this->bitmask += 1 << intval(array_search($bit, $this->keys));
            }
        }
    }

    /** @throws UnknownEnumException */
    public function unset(UnitEnum ...$bits): void
    {
        foreach ($bits as $bit) {
            if ($this->isSet($bit)) {
                $this->bitmask -= 1 << intval(array_search($bit, $this->keys));
            }
        }
    }

    /** @throws UnknownEnumException */
    public function isSet(UnitEnum ...$bits): bool
    {
        foreach ($bits as $bit) {
            $this->checkEnumCase($bit);
            $mask = 1 << intval(array_search($bit, $this->keys));
            if (($this->bitmask & $mask) !== $mask) {
                return false;
            }
        }
        return true;
    }

    /** @throws UnknownEnumException */
    private function checkEnumCase(UnitEnum $case): void
    {
        $case instanceof $this->maskEnum ||
        throw new UnknownEnumException(sprintf('Expected %s enum case, %s provided', $this->maskEnum, $case::class));
    }
}
