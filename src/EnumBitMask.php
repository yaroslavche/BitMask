<?php

declare(strict_types=1);

namespace BitMask;

use BitMask\Exception\UnknownEnumException;
use BitMask\Util\Bits;
use UnitEnum;

final class EnumBitMask extends BitMask implements BitMaskInterface
{
    /** @var array<string, int> $map case => bit */
    private array $map = [];

    /**
     * @param class-string $enum
     * @throws UnknownEnumException
     */
    public function __construct(
        private readonly string $enum,
        protected int $mask = 0,
    ) {
        if (!is_subclass_of($this->enum, UnitEnum::class)) {
            throw new UnknownEnumException('EnumBitMask enum must be subclass of UnitEnum');
        }
        foreach ($this->enum::cases() as $index => $case) {
            $this->map[strval($case->name)] = Bits::indexToBit($index);
        }
        parent::__construct($this->mask, count($this->enum::cases()) - 1);
    }

    /** @throws UnknownEnumException */
    public function setEnumBits(UnitEnum ...$bits): void
    {
        $this->isSetEnumBits(...$bits);
        $this->setBits(...$this->enumBitsToBits(...$bits));
    }

    /** @throws UnknownEnumException */
    public function unsetEnumBits(UnitEnum ...$bits): void
    {
        $this->isSetEnumBits(...$bits);
        $this->unsetBits(...$this->enumBitsToBits(...$bits));
    }

    /** @throws UnknownEnumException */
    public function isSetEnumBits(UnitEnum ...$bits): bool
    {
        array_walk(
            $bits,
            fn(UnitEnum $bit) => $bit instanceof $this->enum ||
            throw new UnknownEnumException(sprintf('Expected %s enum, %s provided', $this->enum, $bit::class))
        );
        return $this->isSetBits(...$this->enumBitsToBits(...$bits));
    }

    /** @return int[] */
    private function enumBitsToBits(UnitEnum ...$bits): array
    {
        return array_map(fn(UnitEnum $bit) => $this->map[$bit->name], $bits);
    }
}
