<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\UnknownEnumException;
use BitMask\Util\Bits;
use UnitEnum;

/** @psalm-suppress UnusedClass */
final class EnumBitMask implements BitMaskInterface
{
    private BitMask $bitmask;
    /** @var array<string, int> $map case => bit */
    private array $map = [];

    /**
     * @param class-string $enum
     * @throws UnknownEnumException
     */
    public function __construct(
        private readonly string $enum,
        int $mask = 0,
    ) {
        if (!is_subclass_of($this->enum, UnitEnum::class)) {
            throw new UnknownEnumException('EnumBitMask enum must be subclass of UnitEnum');
        }
        foreach ($this->enum::cases() as $index => $case) {
            $this->map[strval($case->name)] = Bits::indexToBit($index);
        }
        $this->bitmask = new BitMask($mask, count($this->enum::cases()) - 1);
    }

    public function get(): int
    {
        return $this->bitmask->get();
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function set(UnitEnum ...$bits): void
    {
        $this->has(...$bits);
        $this->bitmask->set(...$this->enumToInt(...$bits));
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function remove(UnitEnum ...$bits): void
    {
        $this->has(...$bits);
        foreach ($bits as $bit) {
            if (!$this->bitmask->has(...$this->enumToInt($bit))) {
                continue;
            }
            $this->bitmask->remove(...$this->enumToInt($bit));
        }
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function has(UnitEnum ...$bits): bool
    {
        array_walk(
            $bits,
            fn(UnitEnum $bit) =>
                $bit instanceof $this->enum ?:
                throw new UnknownEnumException(sprintf('Expected %s enum, %s provided', $this->enum, $bit::class))
        );
        /** @psalm-var UnitEnum[] $bits */
        return $this->bitmask->has(...$this->enumToInt(...$bits));
    }

    /** @return int[] */
    private function enumToInt(UnitEnum ...$bits): array
    {
        return array_map(fn(UnitEnum $bit) => $this->map[$bit->name], $bits);
    }
}
