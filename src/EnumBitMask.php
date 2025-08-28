<?php

declare(strict_types=1);

namespace BitMask;

use BackedEnum;
use BitMask\Exception\InvalidEnumException;
use BitMask\Exception\NotSingleBitException;
use BitMask\Exception\UnknownEnumException;
use BitMask\Util\Bits;
use UnitEnum;

final class EnumBitMask implements BitMaskInterface
{
    private BitMask $bitmask;

    /** @var array<string, int> $map case => bit */
    private array $map = [];

    /**
     * @param class-string $enum
     * @throws InvalidEnumException
     * @throws UnknownEnumException
     */
    public function __construct(
        private readonly string $enum,
        int $mask = 0,
    ) {
        if (!is_subclass_of($this->enum, UnitEnum::class)) {
            throw new UnknownEnumException('EnumBitMask enum must be subclass of UnitEnum');
        }

        $reflection = new \ReflectionEnum($this->enum);
        /** @var null|\ReflectionNamedType $backingType */
        $backingType = $reflection->getBackingType();
        $backingTypeName = $backingType?->getName();
        if ($reflection->isBacked() && $backingTypeName === 'int') {
            /** @var \ReflectionEnumBackedCase $case */
            foreach ($reflection->getCases() as $case) {
                $value = $case->getBackingValue();
                if (!is_int($value)) {
                    throw new InvalidEnumException('Enum must be an int-backed enum with integer values');
                }

                if (!Bits::isSingleBit($value)) {
                    throw new InvalidEnumException(sprintf('Enum case "%s" value is not a single bit', $case->name));
                }

                $this->map[$case->name] = $value;
            }
        } else {
            foreach ($this->enum::cases() as $index => $case) {
                $this->map[$case->name] = Bits::indexToBit($index);
            }
        }

        $this->bitmask = new BitMask($mask, count($this->map) > 0 ? Bits::getMostSignificantBit(max($this->map)) : null);
    }

    /**
     * Create an instance with given flags on
     *
     * @param class-string $enum
     * @throws UnknownEnumException|NotSingleBitException|InvalidEnumException
     */
    public static function create(string $enum, UnitEnum ...$bits): self
    {
        return (new self($enum))->set(...$bits);
    }

    /**
     * Create an instance with all flags on
     *
     * @psalm-suppress MixedMethodCall, MixedArgument
     * @param class-string $enum
     * @throws UnknownEnumException|NotSingleBitException|InvalidEnumException
     */
    public static function all(string $enum): self
    {
        return self::create($enum, ...$enum::cases());
    }

    /**
     * Create an instance with no flags on
     *
     * @psalm-suppress PossiblyUnusedMethod
     * @param class-string $enum
     * @throws UnknownEnumException|NotSingleBitException|InvalidEnumException
     */
    public static function none(string $enum): self
    {
        return new self($enum);
    }

    /**
     * Create an instance without given flags on
     *
     * @psalm-suppress PossiblyUnusedMethod
     * @param class-string $enum
     * @throws UnknownEnumException|NotSingleBitException|InvalidEnumException
     */
    public static function without(string $enum, UnitEnum ...$bits): self
    {
        return self::all($enum)->remove(...$bits);
    }

    #[\Override]
    public function get(): int
    {
        return $this->bitmask->get();
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function set(UnitEnum ...$bits): self
    {
        $this->has(...$bits);
        $this->bitmask->set(...$this->enumToInt(...$bits));

        return $this;
    }

    /** @throws UnknownEnumException|NotSingleBitException */
    public function remove(UnitEnum ...$bits): self
    {
        $this->has(...$bits);
        $this->bitmask->remove(...$this->enumToInt(...$bits));

        return $this;
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     * @throws UnknownEnumException|NotSingleBitException
     */
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
