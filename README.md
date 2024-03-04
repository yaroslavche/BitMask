[![PHP build](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml/badge.svg)](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/yaroslavche/bitmask/branch/main/graph/badge.svg)](https://codecov.io/gh/yaroslavche/bitmask)
[![Infection MSI](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyaroslavche%2FBitMask%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/yaroslavche/BitMask/main)
[![PHP](http://poser.pugx.org/yaroslavche/bitmask/require/php)](https://packagist.org/packages/yaroslavche/bitmask)
# BitMask

PHP library for working with bitmask values

## Getting Started
Usually enough for checking bits: 
```php
define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);

$mask = READ | WRITE | EXECUTE;
echo sprintf('mask: %d', $mask); // mask: 7
if ($mask & READ) {} // if $mask has a single bit READ
$mask ^= EXECUTE; // remove a single bit from the $mask
$mask |= EXECUTE; // set a single bit to the $mask
```

But you can try other way with this package:

```php
use BitMask\BitMask;

// two arguments: integer mask (default: 0) and most significant bit for boundaries (default: null) 
$bitmask = new BitMask(READ | WRITE | EXECUTE);
echo sprintf('mask: %d', $bitmask->get()); // mask: 7
if ($bitmask->has(READ)) {}
$bitmask->remove(EXECUTE);
$bitmask->set(EXECUTE);
```

Exists [EnumBitMask](/src/EnumBitMask.php), which allows the same using PHP enum:

```php
use BitMask\EnumBitMask;

enum Permissions
{
    case READ;
    case WRITE;
    case EXECUTE;
}

// two arguments: required enum class-string and integer mask (default: 0)
$bitmask = new EnumBitMask(Permissions::class, 0b111);
echo sprintf('mask: %d', $bitmask->get()); // mask: 7
if ($bitmask->has(Permissions::READ)) {}
$bitmask->remove(Permissions::EXECUTE);
$bitmask->set(Permissions::EXECUTE);

$bitmask->set(Unknown::Case); // throws an exception, only Permissions cases available
```

Exists [Bits](/src/Util/Bits.php) helper with static methods:

```php
use BitMask\Util\Bits;

$mask = 7; // 1 << 0 | 1 << 1 | 1 << 2
$integerMostSignificantBit = Bits::getMostSignificantBit($mask); // int 2
$arraySetBitsIndexes = Bits::getSetBitsIndexes($mask); // array:3 [0, 1, 2]
$arraySetBits = Bits::getSetBits($mask); // array:3 [1, 2, 4]
$string = Bits::toString($mask); // string "111"
$integerBit = Bits::indexToBit(16); // int 65536
$integerIndex = Bits::bitToIndex(65536); // int 16
$boolIsSingleBit = Bits::isSingleBit(8); // true
```

## Installing

Install package via [composer](https://getcomposer.org/) 
```bash
composer require yaroslavche/bitmask
```

## Contributing

Feel free to fork or contribute =)

#### Tests
##### PHPUnit
```bash
$ composer phpunit
$ ./vendor/bin/phpunit
```
##### Infection
```bash
$ composer infection
$ ./vendor/bin/infection --min-msi=100 --min-covered-msi=100
```
#### Benchmarks
```bash
$ composer phpbench
$ ./vendor/bin/phpbench run benchmarks --report=default
```
#### Static analyzer and code style
##### PHPStan
```bash
$ composer phpstan
$ ./vendor/bin/phpstan analyse src/ -c phpstan.neon --level=9 --no-progress -vvv --memory-limit=1024M
```
##### PHP-CS
###### Code style check
```bash
$ composer phpcs-check
$ ./vendor/bin/php-cs-fixer check --diff

```
###### Code style fix
```bash
$ composer phpcs-fix
$ ./vendor/bin/php-cs-fixer fix
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
