<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\UnknownEnumException;
use BitMask\Util\Bits;
use UnitEnum;

final class EnumBitMask implements BitMaskInterface
{
    /** @var array<string, int> $map case => bit */
    private array $map = [];
    private BitMask $mask;

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
        $this->mask = new BitMask($mask, count($this->enum::cases()) - 1);
    }

    public function get(): int
    {
        return $this->mask->get();
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function set(UnitEnum ...$bits): void
    {
        $this->has(...$bits);
        $this->mask->set(...$this->enumBitsToBits(...$bits));
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function remove(UnitEnum ...$bits): void
    {
        $this->has(...$bits);
        $this->mask->remove(...$this->enumBitsToBits(...$bits));
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function has(UnitEnum ...$bits): bool
    {
        array_walk(
            $bits,
            fn(UnitEnum $bit) => $bit instanceof $this->enum ||
            throw new UnknownEnumException(sprintf('Expected %s enum, %s provided', $this->enum, $bit::class))
        );
        return $this->mask->has(...$this->enumBitsToBits(...$bits));
    }

    /** @return int[] */
    private function enumBitsToBits(UnitEnum ...$bits): array
    {
        return array_map(fn(UnitEnum $bit) => $this->map[$bit->name], $bits);
    }
}
