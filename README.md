[![PHP](http://poser.pugx.org/yaroslavche/bitmask/require/php)](https://packagist.org/packages/yaroslavche/bitmask)
[![Latest Stable Version](https://poser.pugx.org/yaroslavche/bitmask/v/stable)](https://packagist.org/packages/yaroslavche/bitmask)
[![PHP build](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml/badge.svg)](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/yaroslavche/bitmask/branch/main/graph/badge.svg)](https://codecov.io/gh/yaroslavche/bitmask)
[![Infection MSI](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyaroslavche%2FBitMask%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/yaroslavche/BitMask/main)
[![Total Downloads](https://poser.pugx.org/yaroslavche/bitmask/downloads)](https://packagist.org/packages/yaroslavche/bitmask)
# BitMask

Lightweight PHP library for working with bitmask values with support for PHP enums.

- Zero dependencies
- PHP ≥ 8.1
- Enum-friendly
- Strict validation (single-bit guarantees)

## Installation

```bash
composer require yaroslavche/bitmask
```

## Why BitMask?

Classic bitmask operations are simple but quickly become error-prone and hard to read:
```php
$mask &= ~EXECUTE;
$mask |= READ | WRITE;
```
BitMask provides a small, explicit API and optional enum-based safety without hiding how bitmasks actually work.

## Basic Usage
```php
use BitMask\BitMask;

define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);

$bitmask = new BitMask(READ | WRITE);

$bitmask->set(EXECUTE);
$bitmask->remove(WRITE);

if ($bitmask->has(READ)) {
// ...
}

echo $bitmask->get(); // 5
```

## EnumBitMask (recommended)

Work with bitmasks using PHP enums.
### Unit enums (auto-mapped)
```php
use BitMask\EnumBitMask;

enum Permissions
{
    case READ;
    case WRITE;
    case EXECUTE;
}

$mask = new EnumBitMask(Permissions::class);

$mask->set(Permissions::READ);
$mask->set(Permissions::EXECUTE);

$mask->has(Permissions::WRITE); // false
$mask->get(); // 5
```
Enum cases are mapped automatically based on their position:
`READ` → `1`, `WRITE` → `2`, `EXECUTE` → `4`.

### Int-backed enums
```php
enum Flags: int
{
    case User  = 1;   // 0b0001
    case Admin = 8;   // 0b1000
}

$mask = new EnumBitMask(Flags::class, Flags::User | Flags::Admin);
$mask->get(); // 9
```
Each enum value must represent a single bit.
Otherwise, an `InvalidEnumException` will be thrown.

## Factory Methods
```php
EnumBitMask::create(Permissions::class, Permissions::READ, Permissions::EXECUTE);
EnumBitMask::all(Permissions::class);
EnumBitMask::none(Permissions::class);
EnumBitMask::without(Permissions::class, Permissions::EXECUTE);
```

## Bits Helper

Low-level static helpers for working with integer bitmasks.
```php
use BitMask\Util\Bits;

Bits::getMostSignificantBit(7); // 2
Bits::getSetBitsIndexes(7);     // [0, 1, 2]
Bits::getSetBits(7);            // [1, 2, 4]
Bits::toString(7);              // "111"
Bits::isSingleBit(8);           // true
```

## Contributing

Development and contribution guidelines are described in
[CONTRIBUTING.md](CONTRIBUTING.md)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
